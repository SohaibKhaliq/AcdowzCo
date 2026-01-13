<?php

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\FlashSale;
use Botble\Ecommerce\Supports\FlashSaleSupport;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Forms\StoreForm;
use Botble\Marketplace\Forms\VendorStoreForm;
use Botble\Marketplace\Models\Store;
use Botble\Media\Facades\RvMedia;
use Botble\Menu\Facades\Menu;
use Botble\Newsletter\Facades\Newsletter;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Botble\Theme\Typography\TypographyItem;
use Illuminate\Routing\Events\RouteMatched;
use Theme\Farmart\Supports\Wishlist;

register_page_template([
    'default' => __('Default'),
    'homepage' => __('Homepage'),
    'full-width' => __('Full Width'),
    'coming-soon' => __('Coming Soon'),
]);

RvMedia::addSize('small', 300, 300);

Menu::addMenuLocation('header-navigation', __('Header Navigation'));

function available_socials_store(): array
{
    return [
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'instagram' => 'Instagram',
        'youtube' => 'YouTube',
        'linkedin' => 'Linkedin',
    ];
}

app()->booted(function (): void {
    ThemeSupport::registerSocialLinks();
    ThemeSupport::registerSocialSharing();
    ThemeSupport::registerSiteLogoHeight(45);
    ThemeSupport::registerSiteCopyright();
    ThemeSupport::registerDateFormatOption();
    ThemeSupport::registerToastNotification();

    if (is_plugin_active('newsletter')) {
        Newsletter::registerNewsletterPopup();
    }

    if (is_plugin_active('ecommerce')) {
        EcommerceHelper::registerProductVideo();
        EcommerceHelper::registerProductGalleryOptions();
        EcommerceHelper::registerThemeAssets();
    }

    Theme::typography()
        ->registerFontFamily(new TypographyItem('primary', __('Primary'), 'Mulish'));

    register_sidebar([
        'id' => 'pre_footer_sidebar',
        'name' => __('Top footer sidebar'),
        'description' => __('Widgets in the blog page'),
    ]);

    register_sidebar([
        'id' => 'footer_sidebar',
        'name' => __('Footer sidebar'),
        'description' => __('Widgets in footer sidebar'),
    ]);

    register_sidebar([
        'id' => 'bottom_footer_sidebar',
        'name' => __('Bottom footer sidebar'),
        'description' => __('Widgets in bottom footer sidebar'),
    ]);

    if (is_plugin_active('ecommerce')) {
        register_sidebar([
            'id' => 'products_list_sidebar',
            'name' => __('Products list sidebar'),
            'description' => __('Widgets on header products list page'),
        ]);

        register_sidebar([
            'id' => 'product_detail_sidebar',
            'name' => __('Product detail sidebar'),
            'description' => __('Widgets in the product detail page'),
        ]);

        add_filter('ecommerce_quick_view_data', function (array $data): array {
            return [
                ...$data,
                'wishlistIds' => Wishlist::getWishlistIds([$data['product']->getKey()]),
            ];
        });
    }

    if (method_exists(FlashSaleSupport::class, 'addShowSaleCountLeftSetting')) {
        FlashSale::addShowSaleCountLeftSetting();
    }

    if (is_plugin_active('ecommerce') && is_plugin_active('marketplace')) {
        StoreForm::extend(function (StoreForm $form): void {
            $form->addAfter('cover_image', 'background', MediaImageField::class, [
                'label' => __('Background'),
                'metadata' => true,
                'colspan' => 2,
            ]);
        });

        VendorStoreForm::extend(function (VendorStoreForm $form): void {
            $form
                ->addAfter('cover_image', 'background', MediaImageField::class, [
                    'label' => __('Background'),
                    'metadata' => true,
                    'colspan' => 2,
                ])
                ->when(! MarketplaceHelper::hideStoreSocialLinks(), function (VendorStoreForm $form): void {
                    /**
                     * @var Store $store
                     */
                    $store = $form->getModel();

                    $background = $store->getMetaData('background', true);
                    $socials = $store->getMetaData('socials', true);
                    $availableSocials = available_socials_store();

                    $view = Theme::getThemeNamespace() . '::views.marketplace.includes.extended-info-content';

                    $form->addBefore('submit', 'extended_info_content', HtmlField::class, [
                        'html' => view($view, compact('background', 'socials', 'availableSocials'))->render(),
                    ]);
                });
        });

        VendorStoreForm::afterSaving(function (VendorStoreForm $form): void {
            $request = $form->getRequest();

            $store = $form->getModel();

            if ($request->hasFile('background_input')) {
                $result = RvMedia::handleUpload($request->file('background_input'), 0, 'stores');
                if (! $result['error']) {
                    MetaBox::saveMetaBoxData($store, 'background', $result['data']->url);
                }
            } elseif ($request->input('background')) {
                MetaBox::saveMetaBoxData($store, 'background', $request->input('background'));
            } elseif ($request->has('background')) {
                MetaBox::deleteMetaData($store, 'background');
            }

            if (! MarketplaceHelper::hideStoreSocialLinks() && $request->has('socials')) {
                $availableSocials = available_socials_store();
                $socials = collect((array) $request->input('socials', []))->filter(
                    function ($value, $key) use ($availableSocials) {
                        return filter_var($value, FILTER_VALIDATE_URL) && in_array($key, array_keys($availableSocials));
                    }
                );

                MetaBox::saveMetaBoxData($store, 'socials', $socials);
            }
        }, 230);
    }

    app('events')->listen(RouteMatched::class, function (): void {
        EmailHandler::addTemplateSettings(Theme::getThemeName(), [
            'name' => __('Theme emails'),
            'description' => __('Config email templates for theme'),
            'templates' => [
                'contact-seller' => [
                    'title' => __('Contact Seller'),
                    'description' => __(
                        'Email will be sent to the seller when someone contact from store profile page'
                    ),
                    'subject' => __('Message sent via your market profile on {{ site_title }}'),
                    'can_off' => true,
                ],
            ],
            'variables' => [
                'contact_message' => __('Contact seller message'),
                'customer_name' => __('Customer Name'),
                'customer_email' => __('Customer Email'),
                'store_name' => 'plugins/marketplace::marketplace.store_name',
                'store_phone' => 'plugins/marketplace::marketplace.store_phone',
                'store_address' => 'plugins/marketplace::marketplace.store_address',
                'store_url' => 'plugins/marketplace::marketplace.store_url',
                'store' => 'Store',
            ],
        ], 'themes');
    });
});

if (! function_exists('theme_get_autoplay_speed_options')) {
    function theme_get_autoplay_speed_options(): array
    {
        $options = [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000];

        return array_combine($options, $options);
    }
}

if (! function_exists('get_store_list_layouts')) {
    function get_store_list_layouts(): array
    {
        return [
            'grid' => __('Grid'),
            'list' => __('List'),
        ];
    }
}
