<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Http\Middleware\DisableInDemoModeMiddleware;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Controllers\Customers\ExportCustomerController;
use Botble\Ecommerce\Http\Controllers\Customers\ImportCustomerController;
use Botble\Ecommerce\Http\Controllers\Customers\OrderController;
use Botble\Ecommerce\Http\Controllers\Customers\UploadProofController;
use Botble\Ecommerce\Http\Controllers\Fronts\AccountDeletionController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(
    function (): void {
        Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Customers'], function (): void {
            Route::group(['prefix' => 'customers', 'as' => 'customers.'], function (): void {
                Route::resource('', 'CustomerController')->parameters(['' => 'customer']);

                Route::get('view/{id}', [
                    'as' => 'view',
                    'uses' => 'CustomerController@view',
                    'permission' => 'customers.index',
                ])->wherePrimaryKey();

                Route::group(
                    ['prefix' => 'addresses', 'as' => 'addresses.', 'permission' => 'customers.edit'],
                    function (): void {
                        Route::resource('', 'AddressController')->parameters(['' => 'address'])->except(['index']);
                    }
                );

                Route::get('get-list-customers-for-select', [
                    'as' => 'get-list-customers-for-select',
                    'uses' => 'CustomerController@getListCustomerForSelect',
                    'permission' => 'customers.index',
                ]);

                Route::get('get-list-customers-for-search', [
                    'as' => 'get-list-customers-for-search',
                    'uses' => 'CustomerController@getListCustomerForSearch',
                    'permission' => ['customers.index', 'orders.index'],
                ]);

                Route::post('update-email/{id}', [
                    'as' => 'update-email',
                    'uses' => 'CustomerController@postUpdateEmail',
                    'permission' => 'customers.edit',
                ])->wherePrimaryKey();

                Route::get('get-customer-addresses/{id}', [
                    'as' => 'get-customer-addresses',
                    'uses' => 'CustomerController@getCustomerAddresses',
                    'permission' => ['customers.index', 'orders.index'],
                ])->wherePrimaryKey();

                Route::get('get-customer-order-numbers/{id}', [
                    'as' => 'get-customer-order-numbers',
                    'uses' => 'CustomerController@getCustomerOrderNumbers',
                    'permission' => ['customers.index', 'orders.index'],
                ])->wherePrimaryKey();

                Route::post('create-customer-when-creating-order', [
                    'as' => 'create-customer-when-creating-order',
                    'uses' => 'CustomerController@postCreateCustomerWhenCreatingOrder',
                    'permission' => ['customers.index', 'orders.index'],
                ]);

                Route::post('verify-email/{id}', [
                    'as' => 'verify-email',
                    'uses' => 'CustomerController@verifyEmail',
                    'permission' => 'customers.index',
                ])->wherePrimaryKey();

                Route::post('resend-verification-email/{id}', [
                    'as' => 'resend-verification-email',
                    'uses' => 'CustomerController@resendVerificationEmail',
                    'permission' => 'customers.edit',
                ])->wherePrimaryKey();

                Route::post('reviews/{id}', [
                    'as' => 'ajax.reviews',
                    'uses' => 'CustomerController@ajaxReviews',
                    'permission' => 'customers.edit',
                ])->wherePrimaryKey();
            });
        });

        Route::group(['prefix' => 'tools/data-synchronize/import/customers', 'as' => 'ecommerce.customers.import.', 'permission' => 'ecommerce.customers.import'], function (): void {
            Route::get('/', [ImportCustomerController::class, 'index'])->name('index');
            Route::post('validate', [ImportCustomerController::class, 'validateData'])->name('validate');
            Route::post('import', [ImportCustomerController::class, 'import'])->name('store');
            Route::post('download-example', [ImportCustomerController::class, 'downloadExample'])->name('download-example');
        });

        Route::group(['prefix' => 'tools/data-synchronize/export/customers', 'as' => 'ecommerce.customers.export.', 'permission' => 'ecommerce.customers.export'], function (): void {
            Route::get('/', [ExportCustomerController::class, 'index'])->name('index');
            Route::post('/', [ExportCustomerController::class, 'store'])->name('store');
        });
    }
);

Theme::registerRoutes(function (): void {
    Route::group([
        'namespace' => 'Botble\Ecommerce\Http\Controllers\Customers',
        'middleware' => ['customer.guest'],
        'as' => 'customer.',
    ], function (): void {
        Route::get(EcommerceHelper::getPageSlug('login'), 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.post');

        if (EcommerceHelper::isCustomerRegistrationEnabled()) {
            Route::get(EcommerceHelper::getPageSlug('register'), 'RegisterController@showRegistrationForm')->name(
                'register'
            );
            Route::post('register', 'RegisterController@register')->name('register.post');
        }

        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.request');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset.post');
        Route::get(
            EcommerceHelper::getPageSlug('reset_password'),
            'ForgotPasswordController@showLinkRequestForm'
        )->name('password.reset');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')
            ->name('password.reset.update');
    });

    Route::group([
        'namespace' => 'Botble\Ecommerce\Http\Controllers\Customers',
        'middleware' => [
            'web',
            'core',
            EcommerceHelper::isEnableEmailVerification() ? 'customer' : 'customer.guest',
        ],
        'as' => 'customer.',
    ], function (): void {
        Route::get('register/confirm/resend', 'RegisterController@resendConfirmation')
            ->name('resend_confirmation');
        Route::get('register/confirm/{user}', 'RegisterController@confirm')
            ->name('confirm');
    });

    Route::middleware('customer')
        ->namespace('Botble\Ecommerce\Http\Controllers\Customers')
        ->name('customer.')
        ->group(function (): void {
            Route::get('logout', 'LoginController@logout')->name('logout');

            Route::prefix('customer')->group(function (): void {
                Route::post('avatar', [
                    'as' => 'avatar',
                    'uses' => 'PublicController@postAvatar',
                ]);

                Route::group([
                    'prefix' => 'invoices',
                    'as' => 'invoices.',
                ], function (): void {
                    Route::resource('', 'InvoiceController')
                        ->only('index')
                        ->parameters('invoices');
                    Route::get('{id}', 'InvoiceController@show')->name('show')->wherePrimaryKey();
                    Route::get('{id}/generate-invoice', 'InvoiceController@getGenerateInvoice')
                        ->name('generate_invoice')
                        ->wherePrimaryKey();
                });
            });

            Route::get(EcommerceHelper::getPageSlug('customer_overview'), [
                'as' => 'overview',
                'uses' => 'PublicController@getOverview',
            ]);

            Route::prefix(EcommerceHelper::getPageSlug('customer_edit_account'))->group(function (): void {
                Route::get('/', [
                    'as' => 'edit-account',
                    'uses' => 'PublicController@getEditAccount',
                ]);

                Route::post('/', [
                    'as' => 'edit-account.post',
                    'uses' => 'PublicController@postEditAccount',
                ]);
            });

            Route::prefix(EcommerceHelper::getPageSlug('customer_change_password'))->group(function (): void {
                Route::get('/', [
                    'as' => 'change-password',
                    'uses' => 'PublicController@getChangePassword',
                ]);

                Route::post('/', [
                    'as' => 'post.change-password',
                    'uses' => 'PublicController@postChangePassword',
                ]);
            });

            Route::prefix('delete-account')->name('delete-account.')->group(function (): void {
                Route::post('/', [AccountDeletionController::class, 'store'])
                    ->middleware(DisableInDemoModeMiddleware::class)
                    ->name('store');
                Route::get('confirm/{token}', [AccountDeletionController::class, 'confirm'])
                    ->middleware(DisableInDemoModeMiddleware::class)
                    ->name('confirm');
            });

            Route::prefix(EcommerceHelper::getPageSlug('customer_orders'))->group(function (): void {
                Route::get('/', [OrderController::class, 'index'])->name('orders');
                Route::get('view/{id}', [OrderController::class, 'show'])->name('orders.view')->wherePrimaryKey();
                Route::get('cancel/{id}', [OrderController::class, 'getCancelOrder'])->name('orders.cancel')->wherePrimaryKey();
                Route::post('cancel/{id}', [OrderController::class, 'destroy'])->name('orders.cancel.post')->wherePrimaryKey();
                Route::get('print/{id}', [OrderController::class, 'print'])->name('print-order')->wherePrimaryKey();
                Route::post('{id}/upload-proof', [UploadProofController::class, 'upload'])->name('orders.upload-proof')->wherePrimaryKey();
                Route::get('{id}/download-proof', [UploadProofController::class, 'download'])->name('orders.download-proof')->wherePrimaryKey();
                Route::post('{id}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery')->wherePrimaryKey();
            });

            Route::prefix(EcommerceHelper::getPageSlug('customer_address'))->group(function (): void {
                Route::get('/', [
                    'as' => 'address',
                    'uses' => 'PublicController@getListAddresses',
                ]);

                Route::get('create', [
                    'as' => 'address.create',
                    'uses' => 'PublicController@getCreateAddress',
                ]);

                Route::post('create', [
                    'as' => 'address.create.post',
                    'uses' => 'PublicController@postCreateAddress',
                ]);

                Route::get('edit/{id}', [
                    'as' => 'address.edit',
                    'uses' => 'PublicController@getEditAddress',
                ])->wherePrimaryKey();

                Route::post('edit/{id}', [
                    'as' => 'address.edit.post',
                    'uses' => 'PublicController@postEditAddress',
                ])->wherePrimaryKey();

                Route::get('delete/{id}', [
                    'as' => 'address.destroy',
                    'uses' => 'PublicController@getDeleteAddress',
                ])->wherePrimaryKey();
            });

            Route::prefix(EcommerceHelper::getPageSlug('customer_order_returns'))->group(function (): void {
                Route::get('/', [
                    'as' => 'order_returns',
                    'uses' => 'PublicController@getListReturnOrders',
                ]);

                Route::get('detail/{id}', [
                    'as' => 'order_returns.detail',
                    'uses' => 'PublicController@getDetailReturnOrder',
                ])->wherePrimaryKey();

                Route::get('request/{order_id}', [
                    'as' => 'order_returns.request_view',
                    'uses' => 'PublicController@getReturnOrder',
                ])->wherePrimaryKey('order_id');

                Route::post('send-request', [
                    'as' => 'order_returns.send_request',
                    'uses' => 'PublicController@postReturnOrder',
                ]);
            });

            Route::prefix(EcommerceHelper::getPageSlug('customer_downloads'))->group(function (): void {
                Route::get('/', [
                    'as' => 'downloads',
                    'uses' => 'PublicController@getDownloads',
                ]);

                Route::get('download/{id}', [
                    'as' => 'downloads.product',
                    'uses' => 'PublicController@getDownload',
                ])->wherePrimaryKey();
            });

            Route::get(EcommerceHelper::getPageSlug('customer_product_reviews'), [
                'as' => 'product-reviews',
                'uses' => 'PublicController@getProductReviews',
            ]);
        });

    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Customers', 'as' => 'public.'], function (): void {
        Route::get('digital-products/download/{id}', [
            'as' => 'digital-products.download',
            'uses' => 'PublicController@getDownload',
        ]);
    });
});
