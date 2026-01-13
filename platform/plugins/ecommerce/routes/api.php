<?php

use Botble\Ecommerce\Http\Controllers\API\AddressController;
use Botble\Ecommerce\Http\Controllers\API\BrandController;
use Botble\Ecommerce\Http\Controllers\API\CartController;
use Botble\Ecommerce\Http\Controllers\API\CheckoutController;
use Botble\Ecommerce\Http\Controllers\API\CompareController;
use Botble\Ecommerce\Http\Controllers\API\CouponController;
use Botble\Ecommerce\Http\Controllers\API\CountryController;
use Botble\Ecommerce\Http\Controllers\API\CurrencyController;
use Botble\Ecommerce\Http\Controllers\API\DownloadController;
use Botble\Ecommerce\Http\Controllers\API\FilterController;
use Botble\Ecommerce\Http\Controllers\API\FlashSaleController;
use Botble\Ecommerce\Http\Controllers\API\OrderController;
use Botble\Ecommerce\Http\Controllers\API\OrderReturnController;
use Botble\Ecommerce\Http\Controllers\API\OrderTrackingController;
use Botble\Ecommerce\Http\Controllers\API\ProductCategoryController;
use Botble\Ecommerce\Http\Controllers\API\ProductController;
use Botble\Ecommerce\Http\Controllers\API\ReviewController;
use Botble\Ecommerce\Http\Controllers\API\TaxController;
use Botble\Ecommerce\Http\Controllers\API\WishlistController;
use Botble\Ecommerce\Http\Middleware\ApiCurrencyMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api', ApiCurrencyMiddleware::class, 'api.language.ecommerce'],
    'prefix' => 'api/v1/ecommerce/',
    'namespace' => 'Botble\Ecommerce\Http\Controllers\API',
], function (): void {
    // Public routes that use token-based security
    Route::get('download/{token}/{order_id}', [DownloadController::class, 'downloadFile'])->name('api.ecommerce.download.download-file');
    Route::get('orders/download-proof/{token}/{order_id}', [OrderController::class, 'downloadProofFile'])->name('api.ecommerce.orders.download-proof-file');
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{slug}', [ProductController::class, 'show']);
    Route::get('products/{slug}/related', [ProductController::class, 'relatedProducts']);
    Route::get('products/{slug}/cross-sale', [ProductController::class, 'getCrossSaleProducts']);
    Route::get('products/{slug}/reviews', [ProductController::class, 'reviews']);
    Route::get('product-variation/{id}', [ProductController::class, 'getProductVariation'])->wherePrimaryKey();

    Route::get('product-categories', [ProductCategoryController::class, 'index']);
    Route::get('product-categories/{slug}', [ProductCategoryController::class, 'show']);
    Route::get('product-categories/{id}/products', [ProductCategoryController::class, 'products'])->wherePrimaryKey();

    Route::get('brands', [BrandController::class, 'index']);
    Route::get('brands/{slug}', [BrandController::class, 'show']);
    Route::get('brands/{id}/products', [BrandController::class, 'products'])->wherePrimaryKey();

    Route::get('filters', [FilterController::class, 'getFilters']);

    Route::get('flash-sales', [FlashSaleController::class, 'index']);

    Route::group(['middleware' => ['auth:sanctum']], function (): void {
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show'])->wherePrimaryKey();
        Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->wherePrimaryKey();
        Route::get('orders/{id}/invoice', [OrderController::class, 'getInvoice'])->wherePrimaryKey();
        Route::post('orders/{id}/upload-proof', [OrderController::class, 'uploadProof'])->wherePrimaryKey();
        Route::get('orders/{id}/download-proof', [OrderController::class, 'downloadProof'])->wherePrimaryKey();
        Route::post('orders/{id}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->wherePrimaryKey();
        Route::get('addresses', [AddressController::class, 'index']);
        Route::post('addresses', [AddressController::class, 'store']);
        Route::put('addresses/{id}', [AddressController::class, 'update'])->wherePrimaryKey();
        Route::delete('addresses/{id}', [AddressController::class, 'destroy'])->wherePrimaryKey();
        Route::get('reviews', [ReviewController::class, 'index']);
        Route::post('reviews', [ReviewController::class, 'store']);
        Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->wherePrimaryKey();
        Route::get('order-returns', [OrderReturnController::class, 'index']);
        Route::get('order-returns/{id}', [OrderReturnController::class, 'show'])->wherePrimaryKey();
        Route::post('order-returns', [OrderReturnController::class, 'store']);
        Route::get('orders/{order_id}/returns', [OrderReturnController::class, 'getReturnOrder'])->wherePrimaryKey('order_id');

        Route::get('downloads', [DownloadController::class, 'index']);
        Route::get('downloads/{id}', [DownloadController::class, 'download'])->wherePrimaryKey();
    });

    Route::group(['middleware' => ['api.optional.auth']], function (): void {
        Route::post('cart', [CartController::class, 'store']);
        Route::post('cart/{id}', [CartController::class, 'store']);
        Route::put('cart/{id}', [CartController::class, 'update']);
        Route::delete('cart/{id}', [CartController::class, 'destroy']);
        Route::get('cart/{id}', [CartController::class, 'index']);
        Route::post('cart/refresh', [CartController::class, 'refresh']);
    });

    Route::get('coupons', [CouponController::class, 'index']);
    
    Route::group(['middleware' => ['api.optional.auth']], function (): void {
        Route::post('coupon/apply', [CouponController::class, 'apply']);
        Route::post('coupon/remove', [CouponController::class, 'remove']);
    });
    Route::get('countries', [CountryController::class, 'index']);
    Route::get('currencies', [CurrencyController::class, 'index']);
    Route::get('currencies/current', [CurrencyController::class, 'getCurrentCurrency']);

    Route::post('checkout/taxes/calculate', TaxController::class);

    Route::group(['middleware' => ['web', 'core']], function (): void {
        Route::get('checkout/cart/{id}', [CheckoutController::class, 'process']);
    });

    Route::post('orders/tracking', OrderTrackingController::class);

    Route::post('wishlist', [WishlistController::class, 'store']);
    Route::post('wishlist/{id}', [WishlistController::class, 'store']);
    Route::delete('wishlist/{id}', [WishlistController::class, 'destroy']);
    Route::get('wishlist/{id}', [WishlistController::class, 'index']);

    Route::post('compare', [CompareController::class, 'store']);
    Route::post('compare/{id}', [CompareController::class, 'store']);
    Route::delete('compare/{id}', [CompareController::class, 'destroy']);
    Route::get('compare/{id}', [CompareController::class, 'index']);
});
