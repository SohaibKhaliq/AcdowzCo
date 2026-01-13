<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Ecommerce\Http\Controllers\SpecificationAttributeController;
use Botble\Ecommerce\Http\Controllers\SpecificationGroupController;
use Botble\Ecommerce\Http\Controllers\SpecificationTableController;
use Botble\Ecommerce\Http\Middleware\CheckProductSpecificationEnabledMiddleware;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::prefix('ecommerce')
        ->name('ecommerce.')
        ->middleware(CheckProductSpecificationEnabledMiddleware::class)
        ->group(function (): void {
            Route::prefix('specification-groups')->name('specification-groups.')->group(function (): void {
                Route::resource('/', SpecificationGroupController::class)->parameters(['' => 'group']);
            });
            Route::prefix('specification-attributes')->name('specification-attributes.')->group(function (): void {
                Route::resource('/', SpecificationAttributeController::class)->parameters(['' => 'attribute']);
            });
            Route::prefix('specification-tables')->name('specification-tables.')->group(function (): void {
                Route::resource('/', SpecificationTableController::class)->parameters(['' => 'table']);
            });
        });
});
