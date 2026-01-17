<?php

use Botble\Marketplace\Http\Controllers\EnhancedProductOversightController;
use Botble\Ecommerce\Http\Controllers\Fronts\GoogleOAuthController;
use Illuminate\Support\Facades\Route;

// Admin Routes for Enhanced Product Oversight
AdminHelper::registerRoutes(function (): void {
    Route::group([
        'namespace' => 'Botble\Marketplace\Http\Controllers',
        'prefix' => 'marketplace',
        'middleware' => ['web', 'core']
    ], function (): void {

        Route::group(['prefix' => 'product-oversight', 'as' => 'product-oversight.'], function (): void {
            Route::get('/', [EnhancedProductOversightController::class, 'index'])
                ->name('index')
                ->permission('marketplace.vendor-warnings.index');

            Route::post('/bulk-approve', [EnhancedProductOversightController::class, 'bulkApprove'])
                ->name('bulk-approve')
                ->permission('products.edit');

            Route::post('/bulk-reject', [EnhancedProductOversightController::class, 'bulkReject'])
                ->name('bulk-reject')
                ->permission('products.edit');

            Route::post('/bulk-delete', [EnhancedProductOversightController::class, 'bulkDelete'])
                ->name('bulk-delete')
                ->permission('products.delete');

            Route::post('/issue-warning', [EnhancedProductOversightController::class, 'issueWarning'])
                ->name('issue-warning')
                ->permission('marketplace.vendor-warnings.create');

            Route::get('/revenue-breakdown', [EnhancedProductOversightController::class, 'getRevenueBreakdown'])
                ->name('revenue-breakdown')
                ->permission('marketplace.vendors.index');
        });
    });
});

// Customer OAuth Routes
Route::group([
    'namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts',
    'middleware' => ['web', 'core'],
    'prefix' => 'customer/oauth'
], function (): void {
    Route::get('/google', [GoogleOAuthController::class, 'redirectToGoogle'])
        ->name('customer.oauth.google');

    Route::get('/google/callback', [GoogleOAuthController::class, 'handleGoogleCallback'])
        ->name('customer.oauth.google.callback');
});

// OTP Routes for phone verification
Route::group([
    'namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts',
    'middleware' => ['web', 'core'],
    'prefix' => 'customer/otp'
], function (): void {
    Route::post('/send', [\Botble\Ecommerce\Http\Controllers\Fronts\OtpController::class, 'sendOtp'])
        ->name('customer.otp.send');

    Route::post('/verify', [\Botble\Ecommerce\Http\Controllers\Fronts\OtpController::class, 'verifyOtp'])
        ->name('customer.otp.verify');

    Route::post('/resend', [\Botble\Ecommerce\Http\Controllers\Fronts\OtpController::class, 'resendOtp'])
        ->name('customer.otp.resend');
});

// Enhanced Buy Now Routes (if needed)
Route::group([
    'namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts',
    'middleware' => ['web', 'core'],
], function (): void {
    // Additional buy now enhancements can be added here
});
