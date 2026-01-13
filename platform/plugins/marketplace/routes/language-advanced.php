<?php

use Botble\LanguageAdvanced\Http\Controllers\LanguageAdvancedController;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => LanguageAdvancedController::class,
    'middleware' => ['web', 'core', 'vendor'],
    'prefix' => config('plugins.marketplace.general.vendor_panel_dir', 'vendor'),
    'as' => 'marketplace.vendor.language-advanced.',
], function (): void {
    Route::post('language-advanced/save/{id}', ['as' => 'save', 'uses' => 'save'])->wherePrimaryKey();
});
