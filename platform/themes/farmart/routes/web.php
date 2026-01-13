<?php

use Botble\Base\Http\Middleware\RequiresJsonRequestMiddleware;
use Botble\Ecommerce\Http\Controllers\Fronts\PublicAjaxController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;
use Theme\Farmart\Http\Controllers\FarmartController;

Theme::registerRoutes(function (): void {
    Route::middleware(RequiresJsonRequestMiddleware::class)
        ->prefix('ajax')
        ->name('public.ajax.')
        ->group(function (): void {
            if (is_plugin_active('ecommerce')) {
                Route::get('search-products', [PublicAjaxController::class, 'ajaxSearchProducts'])->name('search-products');
                Route::get('categories-dropdown', [PublicAjaxController::class, 'ajaxGetCategoriesDropdown'])->name('categories-dropdown');
            }

            Route::group(['controller' => FarmartController::class], function (): void {
                Route::get('cart', [
                    'uses' => 'ajaxCart',
                    'as' => 'cart',
                ]);

                Route::get('recently-viewed-products', [
                    'uses' => 'ajaxGetRecentlyViewedProducts',
                    'as' => 'recently-viewed-products',
                ]);

                Route::post('ajax/contact-seller', 'ajaxContactSeller')
                    ->name('contact-seller');

                Route::get('products-by-collection/{id}', 'ajaxGetProductsByCollection')
                    ->name('products-by-collection')
                    ->wherePrimaryKey();

                Route::get('products-by-category/{id}', 'ajaxGetProductsByCategory')
                    ->name('products-by-category')
                    ->wherePrimaryKey();
            });
        });
});

Theme::routes();
