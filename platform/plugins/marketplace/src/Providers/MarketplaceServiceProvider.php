<?php

namespace Botble\Marketplace\Providers;

use Botble\ACL\Models\User;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\Form;
use Botble\Base\Facades\MacroableModels;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Models\BaseModel;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Botble\Base\Supports\Helper;
use Botble\Base\Supports\Language;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\SpecificationAttribute;
use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Ecommerce\Models\SpecificationTable;
use Botble\Ecommerce\PanelSections\SettingEcommercePanelSection;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Middleware\RedirectIfNotVendor;
use Botble\Marketplace\Models\Revenue;
use Botble\Marketplace\Models\Scopes\HideProductsByLockedVendorScope;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\VendorInfo;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Marketplace\Observers\ProductObserver;
use Botble\Marketplace\Repositories\Eloquent\RevenueRepository;
use Botble\Marketplace\Repositories\Eloquent\StoreRepository;
use Botble\Marketplace\Repositories\Eloquent\VendorInfoRepository;
use Botble\Marketplace\Repositories\Eloquent\WithdrawalRepository;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Marketplace\Repositories\Interfaces\VendorInfoInterface;
use Botble\Marketplace\Repositories\Interfaces\WithdrawalInterface;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\SiteMapManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class MarketplaceServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        if (! is_plugin_active('ecommerce')) {
            return;
        }

        $this->app->bind(StoreInterface::class, function () {
            return new StoreRepository(new Store());
        });

        $this->app->bind(RevenueInterface::class, function () {
            return new RevenueRepository(new Revenue());
        });

        $this->app->bind(WithdrawalInterface::class, function () {
            return new WithdrawalRepository(new Withdrawal());
        });

        $this->app->bind(VendorInfoInterface::class, function () {
            return new VendorInfoRepository(new VendorInfo());
        });

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->app['router']->aliasMiddleware('vendor', RedirectIfNotVendor::class);

        AliasLoader::getInstance()->alias('MarketplaceHelper', MarketplaceHelper::class);
    }

    public function boot(): void
    {
        if (! is_plugin_active('ecommerce')) {
            return;
        }

        add_filter(IS_IN_ADMIN_FILTER, [$this, 'setInAdmin'], 128);
        add_filter('ecommerce_checkout_show_shipping_section', [$this, 'filterCheckoutShowShippingSection'], 10);

        $this
            ->setNamespace('plugins/marketplace')
            ->loadAndPublishConfigurations(['permissions', 'email', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->publishAssets()
            ->loadRoutes(['base', 'fronts', 'vendor']);

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Store::class, [
                'name',
                'description',
                'content',
                'address',
                'company',
                'cover_image',
            ]);
        }

        DashboardMenu::beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-marketplace',
                    'priority' => 0,
                    'parent_id' => null,
                    'name' => 'plugins/marketplace::marketplace.name',
                    'icon' => 'ti ti-building-store',
                    'url' => '#',
                    'permissions' => ['marketplace.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-store',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-marketplace',
                    'name' => 'plugins/marketplace::store.name',
                    'icon' => 'ti ti-building-store',
                    'url' => fn () => route('marketplace.store.index'),
                    'permissions' => ['marketplace.store.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-withdrawal',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-marketplace',
                    'name' => 'plugins/marketplace::withdrawal.name',
                    'icon' => 'ti ti-cash-banknote',
                    'url' => fn () => route('marketplace.withdrawal.index'),
                    'permissions' => ['marketplace.withdrawal.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-marketplace-vendors',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-marketplace',
                    'name' => 'plugins/marketplace::marketplace.vendors',
                    'icon' => 'ti ti-users',
                    'url' => fn () => route('marketplace.vendors.index'),
                    'permissions' => ['marketplace.vendors.index'],
                ])
                ->when(
                    MarketplaceHelper::getSetting('verify_vendor', 1),
                    function (DashboardMenuSupport $dashboardMenu): void {
                        $dashboardMenu
                            ->registerItem([
                                'id' => 'cms-plugins-marketplace-unverified-vendor',
                                'priority' => 5,
                                'parent_id' => 'cms-plugins-marketplace',
                                'name' => 'plugins/marketplace::unverified-vendor.name',
                                'icon' => 'ti ti-user-question',
                                'url' => fn () => route('marketplace.unverified-vendors.index'),
                                'permissions' => ['marketplace.unverified-vendors.index'],
                            ]);
                    }
                )
                ->registerItem([
                    'id' => 'cms-plugins-marketplace-reports',
                    'priority' => 0,
                    'parent_id' => 'cms-plugins-marketplace',
                    'name' => 'plugins/marketplace::marketplace.reports.name',
                    'icon' => 'ti ti-chart-bar',
                    'url' => fn () => route('marketplace.reports.index'),
                    'permissions' => ['marketplace.reports'],
                ])
                ->when(
                    MarketplaceHelper::isEnabledMessagingSystem(),
                    function (DashboardMenuSupport $dashboardMenu): void {
                        $dashboardMenu
                            ->registerItem([
                            'id' => 'cms-plugins-marketplace-messages',
                            'priority' => 10,
                            'parent_id' => 'cms-plugins-marketplace',
                            'name' => 'plugins/marketplace::message.name',
                            'icon' => 'ti ti-messages',
                            'url' => fn () => route('marketplace.messages.index'),
                            'permissions' => ['marketplace.messages.index'],
                        ]);
                    }
                );
        });

        DashboardMenu::for('vendor')->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'marketplace.vendor.dashboard',
                    'priority' => 1,
                    'name' => __('Dashboard'),
                    'url' => fn () => route('marketplace.vendor.dashboard'),
                    'icon' => 'ti ti-home',
                ])
                ->registerItem([
                    'id' => 'marketplace.vendor.products',
                    'priority' => 2,
                    'name' => __('Products'),
                    'url' => fn () => route('marketplace.vendor.products.index'),
                    'icon' => 'ti ti-package',
                ])
                ->when(EcommerceHelper::isProductSpecificationEnabled(), function (DashboardMenuSupport $dashboardMenu): void {
                    $dashboardMenu
                        ->registerItem([
                            'id' => 'cms-plugins-product-specification',
                            'priority' => 900,
                            'name' => __('Product Specification'),
                            'icon' => 'ti ti-table-options',
                            'permissions' => ['ecommerce.product-specification.index'],
                        ])
                        ->registerItem([
                            'id' => 'cms-plugins-product-specification-groups',
                            'parent_id' => 'cms-plugins-product-specification',
                            'priority' => 0,
                            'name' => __('Specification Groups'),
                            'url' => fn () => route('marketplace.vendor.specification-groups.index'),
                            'icon' => 'ti ti-folder',
                        ])
                        ->registerItem([
                            'id' => 'cms-plugins-product-specification-attributes',
                            'parent_id' => 'cms-plugins-product-specification',
                            'priority' => 10,
                            'name' => __('Specification Attributes'),
                            'url' => fn () => route('marketplace.vendor.specification-attributes.index'),
                            'icon' => 'ti ti-list-details',
                        ])
                        ->registerItem([
                            'id' => 'cms-plugins-product-specification-tables',
                            'parent_id' => 'cms-plugins-product-specification',
                            'priority' => 20,
                            'name' => __('Specification Tables'),
                            'url' => fn () => route('marketplace.vendor.specification-tables.index'),
                            'icon' => 'ti ti-table',
                        ]);
                })
                ->registerItem([
                    'id' => 'marketplace.vendor.orders',
                    'priority' => 3,
                    'name' => __('Orders'),
                    'url' => fn () => route('marketplace.vendor.orders.index'),
                    'icon' => 'ti ti-shopping-cart',
                ])
                ->registerItem([
                    'id' => 'marketplace.vendor.discounts',
                    'priority' => 4,
                    'name' => __('Coupons'),
                    'url' => fn () => route('marketplace.vendor.discounts.index'),
                    'icon' => 'ti ti-tag',
                ])
                ->registerItem([
                    'id' => 'marketplace.vendor.withdrawals',
                    'priority' => 5,
                    'name' => __('Withdrawals'),
                    'url' => fn () => route('marketplace.vendor.withdrawals.index'),
                    'icon' => 'ti ti-cash',
                ])
                ->registerItem([
                    'id' => 'marketplace.vendor.revenues',
                    'priority' => 6,
                    'name' => __('Revenues'),
                    'url' => fn () => route('marketplace.vendor.revenues.index'),
                    'icon' => 'ti ti-wallet',
                ])
                ->registerItem([
                    'id' => 'marketplace.vendor.settings',
                    'priority' => 999,
                    'name' => __('Settings'),
                    'url' => fn () => route('marketplace.vendor.settings'),
                    'icon' => 'ti ti-settings',
                ])
                ->when(MarketplaceHelper::isEnabledMessagingSystem(), function (DashboardMenuSupport $dashboardMenu) {
                    return $dashboardMenu->registerItem([
                        'id' => 'marketplace.vendor.messages',
                        'priority' => 8,
                        'name' => __('Messages'),
                        'url' => fn () => route('marketplace.vendor.messages.index'),
                        'icon' => 'ti ti-messages',
                    ]);
                })
                ->when(EcommerceHelper::isReviewEnabled(), function (DashboardMenuSupport $dashboardMenu) {
                    return $dashboardMenu->registerItem([
                        'id' => 'marketplace.vendor.reviews',
                        'priority' => 5,
                        'name' => __('Reviews'),
                        'url' => fn () => route('marketplace.vendor.reviews.index'),
                        'icon' => 'ti ti-star',
                    ]);
                })
                ->when(EcommerceHelper::isOrderReturnEnabled(), function (DashboardMenuSupport $dashboardMenu) {
                    return $dashboardMenu->registerItem([
                        'id' => 'marketplace.vendor.order-returns',
                        'priority' => 3,
                        'name' => __('Order Returns'),
                        'url' => fn () => route('marketplace.vendor.order-returns.index'),
                        'icon' => 'ti ti-reload',
                    ]);
                })
                ->when(MarketplaceHelper::allowVendorManageShipping(), function (DashboardMenuSupport $dashboardMenu) {
                    return $dashboardMenu->registerItem([
                        'id' => 'marketplace.vendor.shipments',
                        'priority' => 3,
                        'name' => __('Shipments'),
                        'url' => fn () => route('marketplace.vendor.shipments.index'),
                        'icon' => 'ti ti-truck',
                    ]);
                });
        });

        DashboardMenu::for('customer')->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->when(auth('customer')->user()->is_vendor, function () {
                    return DashboardMenu::make()
                        ->registerItem([
                            'id' => 'marketplace.vendor.dashboard',
                            'priority' => 990,
                            'name' => __('Vendor Dashboard'),
                            'url' => fn () => route('marketplace.vendor.dashboard'),
                            'icon' => 'ti ti-building-store',
                        ]);
                }, function (): void {
                    DashboardMenu::make()
                        ->when(
                            MarketplaceHelper::isVendorRegistrationEnabled()
                            && ! MarketplaceHelper::getSetting('hide_become_vendor_menu_in_customer_dashboard', false),
                            function () {
                                return DashboardMenu::make()
                                    ->registerItem([
                                        'id' => 'marketplace.vendor.become-vendor',
                                        'priority' => 991,
                                        'name' => __('Become A Vendor'),
                                        'url' => fn () => route('marketplace.vendor.become-vendor'),
                                        'icon' => 'ti ti-building-store',
                                    ]);
                            }
                        );
                });
        });

        DashboardMenu::default();

        $this->app['events']->listen(RouteMatched::class, function (): void {
            if (! MarketplaceHelper::getSetting('verify_vendor', 1)) {
                config([
                    'plugins.marketplace.email.templates' => Arr::except(
                        config('plugins.marketplace.email.templates'),
                        'verify_vendor'
                    ),
                ]);
            }
        });

        $this->app->booted(function (): void {
            EmailHandler::addTemplateSettings(
                MARKETPLACE_MODULE_SCREEN_NAME,
                config('plugins.marketplace.email', [])
            );
        });

        PanelSectionManager::beforeRendering(function (): void {
            PanelSectionManager::default()->registerItem(
                SettingEcommercePanelSection::class,
                fn () => PanelSectionItem::make('settings.ecommerce.marketplace')
                    ->setTitle(trans('plugins/ecommerce::setting.marketplace.name'))
                    ->withIcon('ti ti-building-store')
                    ->withDescription(trans('plugins/ecommerce::setting.marketplace.description'))
                    ->withPriority(150)
                    ->withRoute('marketplace.settings'),
            );
        });

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Store::class, fn () => trans('plugins/marketplace::store.stores'));
            SlugHelper::setPrefix(Store::class, 'stores');
        });

        SeoHelper::registerModule([Store::class]);
        SiteMapManager::registerKey('stores');

        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
        $this->app->register(OrderSupportServiceProvider::class);

        $this->app['events']->listen('eloquent.deleted: ' . Customer::class, function (Customer $customer): void {
            Revenue::query()->where('customer_id', $customer->getKey())->delete();
            Withdrawal::query()->where('customer_id', $customer->getKey())->delete();
            VendorInfo::query()->where('customer_id', $customer->getKey())->delete();
            Store::query()->where('customer_id', $customer->getKey())->each(fn (Store $store) => $store->delete());
        });

        $this->app['events']->listen('eloquent.deleted: ' . Customer::class, function (Customer $customer): void {
            if (! $customer->is_vendor) {
                return;
            }

            SpecificationGroup::query()
                ->where('author_type', Customer::class)
                ->where('author_id', $customer->getKey())
                ->delete();

            SpecificationAttribute::query()
                ->where('author_type', Customer::class)
                ->where('author_id', $customer->getKey())
                ->delete();

            SpecificationTable::query()
                ->where('author_type', Customer::class)
                ->where('author_id', $customer->getKey())
                ->delete();
        });

        $this->app->booted(function (): void {
            Customer::resolveRelationUsing('store', function ($model) {
                return $model->hasOne(Store::class)->withDefault();
            });

            Order::resolveRelationUsing('store', function ($model) {
                return $model->belongsTo(Store::class, 'store_id')->withDefault();
            });

            Product::resolveRelationUsing('store', function ($model) {
                return $model->belongsTo(Store::class, 'store_id')->withDefault();
            });

            Product::resolveRelationUsing('approvedBy', function ($model) {
                return $model->belongsTo(User::class, 'approved_by')->withDefault();
            });

            Product::observe(ProductObserver::class);

            Customer::resolveRelationUsing('vendorInfo', function ($model) {
                return $model->hasOne(VendorInfo::class, 'customer_id')->withDefault();
            });

            Discount::resolveRelationUsing('store', function ($model) {
                return $model->belongsTo(Store::class, 'store_id')->withDefault();
            });

            MacroableModels::addMacro(Customer::class, 'getBalanceAttribute', function () {
                /**
                 * @return float
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->balance : 0;
            });

            MacroableModels::addMacro(Customer::class, 'getBankInfoAttribute', function () {
                /**
                 * @return array
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->bank_info : [];
            });

            MacroableModels::addMacro(Customer::class, 'getTaxInfoAttribute', function () {
                /**
                 * @return array
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->tax_info : [];
            });

            MacroableModels::addMacro(Customer::class, 'getTotalFeeAttribute', function () {
                /**
                 * @return float
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->total_fee : 0;
            });

            MacroableModels::addMacro(Customer::class, 'getTotalRevenueAttribute', function () {
                /**
                 * @return float
                 * @var BaseModel $this
                 */
                return $this->vendorInfo ? $this->vendorInfo->total_revenue : 0;
            });

            if (! $this->app->runningInConsole()) {
                Product::addGlobalScope(HideProductsByLockedVendorScope::class);
            }

            if (is_plugin_active('language-advanced')) {
                $this->loadRoutes(['language-advanced']);
            }

            $emailVariables = [
                'store' => 'plugins/marketplace::store.store',
                'store_name' => 'plugins/marketplace::store.store_name',
                'store_address' => 'plugins/marketplace::store.store_address',
                'store_phone' => 'plugins/marketplace::store.store_phone',
                'store_url' => 'plugins/marketplace::store.store_url',
            ];

            $emailTemplates = [
                'plugins.ecommerce.email.templates.customer_new_order.variables',
                'plugins.ecommerce.email.templates.admin_new_order.variables',
                'plugins.ecommerce.email.templates.customer_cancel_order.variables',
                'plugins.ecommerce.email.templates.order_confirm.variables',
                'plugins.ecommerce.email.templates.order_confirm_payment.variables',
                'plugins.ecommerce.email.templates.order_recover.variables',
                'plugins.ecommerce.email.templates.order-return-request.variables',
                'plugins.ecommerce.email.templates.invoice-payment-created.variables',
                'plugins.ecommerce.email.templates.review_products.variables',
                'plugins.ecommerce.email.templates.download_digital_products.variables',
            ];

            if (! EcommerceHelper::isDisabledPhysicalProduct()) {
                $emailTemplates = [
                    ...$emailTemplates,
                    'plugins.ecommerce.email.templates.customer_delivery_order.variables',
                    'plugins.ecommerce.email.templates.customer_order_delivered.variables',
                ];
            }

            $config = $this->app['config'];

            foreach ($emailTemplates as $emailTemplate) {
                $config->set([$emailTemplate => array_merge($config->get($emailTemplate, []), $emailVariables)]);
            }
        });

        Form::component('customEditor', MarketplaceHelper::viewPath('vendor-dashboard.forms.partials.custom-editor'), [
            'name',
            'value' => null,
            'attributes' => [],
        ]);

        Form::component('customImage', MarketplaceHelper::viewPath('vendor-dashboard.forms.partials.custom-image'), [
            'name',
            'value' => null,
            'attributes' => [],
        ]);

        Form::component('customImages', MarketplaceHelper::viewPath('vendor-dashboard.forms.partials.custom-images'), [
            'name',
            'values' => null,
            'attributes' => [],
        ]);
    }

    public function setInAdmin(bool $isInAdmin): bool
    {
        $segment = request()->segment(1);

        if ($segment && in_array($segment, Language::getLocaleKeys())) {
            $segment = request()->segment(2);
        }

        return $segment === config('plugins.marketplace.general.vendor_panel_dir', 'vendor') || $isInAdmin;
    }

    public function filterCheckoutShowShippingSection(bool $showShipping): bool
    {
        // If marketplace is active and charge_shipping_per_vendor is false, show standard shipping
        return ! MarketplaceHelper::isChargeShippingPerVendor();
    }
}
