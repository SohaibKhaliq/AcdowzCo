<?php

use Botble\StripeConnect\Http\Controllers\StripeConnectController;
use Botble\Theme\Facades\Theme;

Theme::registerRoutes(function (): void {
    Route::middleware('customer')->name('stripe-connect.')->prefix('stripe-connect')->group(function (): void {
        Route::get('connect', [StripeConnectController::class, 'connect'])->name('connect');
        Route::get('dashboard', [StripeConnectController::class, 'dashboard'])->name('dashboard');
        Route::get('return', [StripeConnectController::class, 'return'])->name('return');
        Route::get('refresh', [StripeConnectController::class, 'refresh'])->name('refresh');
        Route::get('disconnect', [StripeConnectController::class, 'disconnect'])->name('disconnect');
    });
});
