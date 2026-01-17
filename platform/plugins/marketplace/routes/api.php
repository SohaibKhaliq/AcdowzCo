<?php

use Illuminate\Support\Facades\Route;
use Botble\Marketplace\Http\Controllers\API\MarketplaceApiController;

// Admin API routes - require admin authentication
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::prefix('marketplace/admin')->group(function () {
        Route::get('vendor-stats', [MarketplaceApiController::class, 'getVendorStats']);
        Route::get('product-stats', [MarketplaceApiController::class, 'getProductStats']);
        Route::get('reseller-stats', [MarketplaceApiController::class, 'getResellerStats']);
        Route::get('revenue-breakdown', [MarketplaceApiController::class, 'getRevenueBreakdown']);
        Route::get('top-vendors', [MarketplaceApiController::class, 'getTopVendors']);
        Route::get('warnings-stats', [MarketplaceApiController::class, 'getWarningsStats']);
        Route::get('reseller-activity', [MarketplaceApiController::class, 'getResellerActivity']);
        Route::get('dashboard-metrics', [MarketplaceApiController::class, 'getDashboardMetrics']);
    });
});

// Vendor API routes - require vendor authentication
Route::middleware(['auth:sanctum', 'role:vendor'])->group(function () {
    Route::prefix('marketplace/vendor')->group(function () {
        Route::get('dashboard', [MarketplaceApiController::class, 'getVendorDashboard']);
        Route::get('products', [MarketplaceApiController::class, 'getVendorProducts']);
        Route::get('orders', [MarketplaceApiController::class, 'getVendorOrders']);
        Route::get('revenue', [MarketplaceApiController::class, 'getVendorRevenue']);
    });
});
