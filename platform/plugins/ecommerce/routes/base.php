<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Http\Middleware\RequiresJsonRequestMiddleware;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Controllers\ExportProductCategoryController;
use Botble\Ecommerce\Http\Controllers\Fronts\PublicUpdateCheckoutController;
use Botble\Ecommerce\Http\Controllers\Fronts\PublicUpdateTaxCheckoutController;
use Botble\Ecommerce\Http\Controllers\Fronts\QuickShopController;
use Botble\Ecommerce\Http\Controllers\Fronts\QuickViewController;
use Botble\Ecommerce\Http\Controllers\Fronts\GuestPaymentProofController;
use Botble\Ecommerce\Http\Controllers\ImportProductCategoryController;
use Botble\Ecommerce\Http\Controllers\OrderExportController;
use Botble\Theme\Events\ThemeRoutingBeforeEvent;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function (): void {
        Route::post('update-currencies-from-exchange-api', [
            'as' => 'ecommerce.setting.update-currencies-from-exchange-api',
            'uses' => 'EcommerceController@updateCurrenciesFromExchangeApi',
            'permission' => 'ecommerce.settings',
        ]);

        Route::post('clear-cache-currency-rates', [
            'as' => 'ecommerce.setting.clear-cache-currency-rates',
            'uses' => 'EcommerceController@clearCacheCurrencyRates',
            'permission' => 'ecommerce.settings',
        ]);

        Route::get('ajax/countries', [
            'as' => 'ajax.countries.list',
            'uses' => 'EcommerceController@ajaxGetCountries',
            'permission' => false,
        ]);

        Route::get('store-locators/form/{id?}', [
            'as' => 'ecommerce.store-locators.form',
            'uses' => 'StoreLocatorController@edit',
            'permission' => 'ecommerce.settings',
        ]);

        Route::post('store-locators/edit/{locator}', [
            'as' => 'ecommerce.store-locators.edit.post',
            'uses' => 'StoreLocatorController@update',
            'permission' => 'ecommerce.settings',
        ])->wherePrimaryKey();

        Route::post('store-locators/create', [
            'as' => 'ecommerce.store-locators.create',
            'uses' => 'StoreLocatorController@store',
            'permission' => 'ecommerce.settings',
        ]);

        Route::post('store-locators/delete/{locator}', [
            'as' => 'ecommerce.store-locators.destroy',
            'uses' => 'StoreLocatorController@destroy',
            'permission' => 'ecommerce.settings',
        ])->wherePrimaryKey();

        Route::post('store-locators/update-primary-store', [
            'as' => 'ecommerce.store-locators.update-primary-store',
            'uses' => 'ChangePrimaryStoreController@__invoke',
            'permission' => 'ecommerce.settings',
        ]);

        Route::group(['prefix' => 'product-categories', 'as' => 'product-categories.'], function (): void {
            Route::resource('', 'ProductCategoryController')
                ->parameters(['' => 'product_category']);

            Route::put('update-tree', [
                'as' => 'update-tree',
                'uses' => 'ProductCategoryController@updateTree',
                'permission' => 'product-categories.edit',
            ]);

            Route::get('search', [
                'as' => 'search',
                'uses' => 'ProductCategoryController@getSearch',
                'permission' => 'product-categories.index',
            ]);

            Route::get('get-list-product-categories-for-select', [
                'as' => 'get-list-product-categories-for-select',
                'uses' => 'ProductCategoryController@getListForSelect',
                'permission' => 'product-categories.index',
            ]);
        });

        require core_path('table/routes/web-actions.php');

        Route::group(['prefix' => 'product-tags', 'as' => 'product-tag.'], function (): void {
            Route::resource('', 'ProductTagController')
                ->parameters(['' => 'product-tag']);

            Route::get('all', [
                'as' => 'all',
                'uses' => 'ProductTagController@getAllTags',
                'permission' => 'product-tag.index',
            ]);
        });

        Route::group(['prefix' => 'options', 'as' => 'global-option.'], function (): void {
            Route::resource('', 'ProductOptionController')->parameters(['' => 'option']);

            Route::get('ajax', [
                'as' => 'ajaxInfo',
                'uses' => 'ProductOptionController@ajaxInfo',
                'permission' => 'products.edit',
            ]);
        });

        Route::group(['prefix' => 'brands', 'as' => 'brands.'], function (): void {
            Route::resource('', 'BrandController')
                ->parameters(['' => 'brand']);

            Route::get('search', [
                'as' => 'search',
                'uses' => 'BrandController@getSearch',
                'permission' => 'brands.index',
            ]);
        });

        Route::group(['prefix' => 'product-collections', 'as' => 'product-collections.'], function (): void {
            Route::resource('', 'ProductCollectionController')
                ->parameters(['' => 'product_collection']);

            Route::get('get-list-product-collections-for-select', [
                'as' => 'get-list-product-collections-for-select',
                'uses' => 'ProductCollectionController@getListForSelect',
                'permission' => 'product-collections.index',
            ]);

            Route::get('get-product-collection/{productCollection?}', [
                'as' => 'get-product-collection',
                'uses' => 'ProductCollectionController@getProductCollection',
                'permission' => 'product-collections.edit',
            ])->wherePrimaryKey();
        });

        Route::group(['prefix' => 'product-attribute-sets', 'as' => 'product-attribute-sets.'], function (): void {
            Route::resource('', 'ProductAttributeSetsController')
                ->parameters(['' => 'product_attribute_set']);
        });

        Route::group(['prefix' => 'reports', 'as' => 'ecommerce.report.'], function (): void {
            Route::get('', [
                'as' => 'index',
                'uses' => 'ReportController@getIndex',
            ]);

            Route::post('top-selling-products', [
                'as' => 'top-selling-products',
                'uses' => 'ReportController@getTopSellingProducts',
                'permission' => 'ecommerce.report.index',
            ]);

            Route::post('recent-orders', [
                'as' => 'recent-orders',
                'uses' => 'ReportController@getRecentOrders',
                'permission' => 'ecommerce.report.index',
            ]);

            Route::post('trending-products', [
                'as' => 'trending-products',
                'uses' => 'ReportController@getTrendingProducts',
                'permission' => 'ecommerce.report.index',
            ]);

            Route::get('dashboard-general-report', [
                'as' => 'dashboard-widget.general',
                'uses' => 'ReportController@getDashboardWidgetGeneral',
                'permission' => 'ecommerce.report.index',
            ]);

            Route::group(['prefix' => 'widget-config', 'as' => 'widget-config.'], function (): void {
                Route::get('', [
                    'as' => 'index',
                    'uses' => 'ReportWidgetConfigController@index',
                    'permission' => 'ecommerce.report.index',
                ]);

                Route::post('save', [
                    'as' => 'save',
                    'uses' => 'ReportWidgetConfigController@store',
                    'permission' => 'ecommerce.report.index',
                ]);

                Route::get('get', [
                    'as' => 'get',
                    'uses' => 'ReportWidgetConfigController@getConfiguration',
                    'permission' => 'ecommerce.report.index',
                ]);
            });
        });

        Route::group(['prefix' => 'flash-sales', 'as' => 'flash-sale.'], function (): void {
            Route::resource('', 'FlashSaleController')->parameters(['' => 'flash-sale']);
        });

        Route::group(['prefix' => 'product-labels', 'as' => 'product-label.'], function (): void {
            Route::resource('', 'ProductLabelController')->parameters(['' => 'product-label']);
        });
    });

    Route::prefix('tools/data-synchronize')->name('tools.data-synchronize.')->group(function (): void {
        Route::prefix('export')->name('export.')->group(function (): void {
            Route::group(['prefix' => 'product-categories', 'as' => 'product-categories.', 'permission' => 'product-categories.export'], function (): void {
                Route::get('/', [ExportProductCategoryController::class, 'index'])->name('index');
                Route::post('/', [ExportProductCategoryController::class, 'store'])->name('store');
            });

            Route::group(['prefix' => 'orders', 'as' => 'orders.', 'permission' => 'orders.export'], function (): void {
                Route::get('/', [OrderExportController::class, 'index'])->name('index');
                Route::post('/', [OrderExportController::class, 'store'])->name('store');
            });
        });

        Route::prefix('import')->name('import.')->group(function (): void {
            Route::group(['prefix' => 'product-categories', 'as' => 'product-categories.', 'permission' => 'product-categories.import'], function (): void {
                Route::get('/', [ImportProductCategoryController::class, 'index'])->name('index');
                Route::post('/', [ImportProductCategoryController::class, 'import'])->name('store');
                Route::post('validate', [ImportProductCategoryController::class, 'validateData'])->name('validate');
                Route::post('download-example', [ImportProductCategoryController::class, 'downloadExample'])->name('download-example');
            });
        });
    });
});

Theme::registerRoutes(function (): void {
    app('events')->listen(ThemeRoutingBeforeEvent::class, function (): void {
        Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts'], function (): void {
            Route::get(EcommerceHelper::getPageSlug('product_listing'), [
                'uses' => 'PublicProductController@getProducts',
                'as' => 'public.products',
            ]);

            Route::get('currency/switch/{code?}', [
                'as' => 'public.change-currency',
                'uses' => 'PublicEcommerceController@changeCurrency',
            ]);

            Route::get('product-variation/{id}', [
                'as' => 'public.web.get-variation-by-attributes',
                'uses' => 'PublicProductController@getProductVariation',
            ])->wherePrimaryKey();

            Route::get(EcommerceHelper::getPageSlug('order_tracking'), [
                'as' => 'public.orders.tracking',
                'uses' => 'PublicProductController@getOrderTracking',
            ])->wherePrimaryKey();

            Route::get('ajax/quick-view/{id?}', [QuickViewController::class, 'show'])
                ->middleware(RequiresJsonRequestMiddleware::class)
                ->name('public.ajax.quick-view')
                ->wherePrimaryKey();

            Route::get('ajax/quick-shop/{slug}', [QuickShopController::class, 'show'])
                ->middleware(RequiresJsonRequestMiddleware::class)
                ->name('public.ajax.quick-shop')
                ->wherePrimaryKey();

            Route::post('ajax/checkout/update', [PublicUpdateCheckoutController::class, '__invoke'])
                ->middleware(RequiresJsonRequestMiddleware::class)
                ->name('public.ajax.checkout.update');

            Route::post('ajax/checkout/update-tax', [PublicUpdateTaxCheckoutController::class, '__invoke'])
                ->middleware(RequiresJsonRequestMiddleware::class)
                ->name('public.ajax.checkout.update-tax');

            Route::group(['prefix' => 'orders/payment-proof'], function (): void {
                Route::post('{token}/upload', [GuestPaymentProofController::class, 'upload'])
                    ->name('public.orders.upload-proof-guest');
                Route::get('{token}/download', [GuestPaymentProofController::class, 'download'])
                    ->name('public.orders.download-proof-guest');
            });
        });
    });
});

AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function (): void {
        Route::group(['prefix' => 'reseller-commissions', 'as' => 'reseller-commissions.'], function (): void {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'ResellerCommissionController@index',
                'permission' => 'customers.index',
            ]);

            Route::post('{id}/approve', [
                'as' => 'approve',
                'uses' => 'ResellerCommissionController@approve',
                'permission' => 'customers.edit',
            ])->wherePrimaryKey();

            Route::post('{id}/pay', [
                'as' => 'pay',
                'uses' => 'ResellerCommissionController@pay',
                'permission' => 'customers.edit',
            ])->wherePrimaryKey();

            Route::post('bulk-approve', [
                'as' => 'bulk-approve',
                'uses' => 'ResellerCommissionController@bulkApprove',
                'permission' => 'customers.edit',
            ]);

            Route::post('bulk-pay', [
                'as' => 'bulk-pay',
                'uses' => 'ResellerCommissionController@bulkPay',
                'permission' => 'customers.edit',
            ]);

            Route::get('reseller/{id}', [
                'as' => 'reseller-details',
                'uses' => 'ResellerCommissionController@resellerDetails',
                'permission' => 'customers.index',
            ])->wherePrimaryKey();
        });

        Route::group(['prefix' => 'reseller-penalties', 'as' => 'reseller-penalties.'], function (): void {
            Route::match(['GET', 'POST'], '/', [
                'as' => 'index',
                'uses' => 'ResellerPenaltyController@index',
                'permission' => 'customers.index',
            ]);

            Route::get('create', [
                'as' => 'create',
                'uses' => 'ResellerPenaltyController@create',
                'permission' => 'customers.edit',
            ]);

            Route::post('store', [
                'as' => 'store',
                'uses' => 'ResellerPenaltyController@store',
                'permission' => 'customers.edit',
            ]);

            Route::get('show/{id}', [
                'as' => 'show',
                'uses' => 'ResellerPenaltyController@show',
                'permission' => 'customers.index',
            ])->wherePrimaryKey();

            Route::post('reverse/{id}', [
                'as' => 'reverse',
                'uses' => 'ResellerPenaltyController@reverse',
                'permission' => 'customers.edit',
            ])->wherePrimaryKey();

            Route::get('ajax/orders', [
                'as' => 'ajax.orders',
                'uses' => 'ResellerPenaltyController@getOrders',
                'permission' => 'customers.index',
            ]);
        });

        Route::group(['prefix' => 'reseller-applications', 'as' => 'reseller-applications.'], function (): void {
            Route::resource('', 'Admin\ResellerApplicationController')->parameters(['' => 'reseller_application'])->except(['create', 'store', 'destroy']);
        });

        Route::group(['prefix' => 'reseller-management', 'as' => 'reseller-management.'], function (): void {
            Route::match(['GET', 'POST'], '/', [
                'as' => 'index',
                'uses' => 'Admin\ResellerManagementController@index',
                'permission' => 'customers.index',
            ]);

            Route::match(['GET', 'POST'], 'deletion-requests', [
                'as' => 'deletion-requests',
                'uses' => 'Admin\ResellerManagementController@deletionRequests',
                'permission' => 'customers.index',
            ]);

            Route::match(['GET', 'POST'], '{id}/process-deletion', [
                'as' => 'process-deletion',
                'uses' => 'Admin\ResellerManagementController@processDeletion',
                'permission' => 'customers.edit',
            ])->wherePrimaryKey();

            Route::match(['GET', 'POST'], '{id}/reject-deletion', [
                'as' => 'reject-deletion',
                'uses' => 'Admin\ResellerManagementController@rejectDeletion',
                'permission' => 'customers.edit',
            ])->wherePrimaryKey();

            Route::match(['GET', 'POST'], '{id}/disable', [
                'as' => 'disable',
                'uses' => 'Admin\ResellerManagementController@disableReseller',
                'permission' => 'customers.edit',
            ])->wherePrimaryKey();

            Route::match(['GET', 'POST'], '{id}/enable', [
                'as' => 'enable',
                'uses' => 'Admin\ResellerManagementController@enableReseller',
                'permission' => 'customers.edit',
            ])->wherePrimaryKey();
        });

        Route::group(['prefix' => 'products', 'as' => 'products.'], function (): void {
            Route::get('{id}/countries', [
                'as' => 'countries.index',
                'uses' => 'ProductCountryController@index',
                'permission' => 'product.country.assign',
            ])->wherePrimaryKey();

            Route::post('{id}/countries', [
                'as' => 'countries.store',
                'uses' => 'ProductCountryController@store',
                'permission' => 'product.country.assign',
            ])->wherePrimaryKey();

            Route::post('bulk-assign-countries', [
                'as' => 'bulk-assign-countries',
                'uses' => 'ProductCountryController@bulkAssign',
                'permission' => 'product.country.assign',
            ]);
        });
    });
});
