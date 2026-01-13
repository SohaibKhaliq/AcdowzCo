<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce'], function (): void {
        Route::group(['prefix' => 'taxes', 'as' => 'tax.'], function (): void {
            Route::resource('', 'TaxController')->parameters(['' => 'tax']);

            Route::group(['permission' => 'ecommerce.settings.taxes'], function (): void {
                Route::group(['prefix' => '{tax}/rules', 'as' => 'rule.'], function (): void {
                    Route::resource('', 'TaxRuleController')
                        ->parameters(['' => 'rule'])
                        ->only(['index']);
                });

                Route::group(['prefix' => 'rules', 'as' => 'rule.'], function (): void {
                    Route::resource('', 'TaxRuleController')
                        ->parameters(['' => 'rule'])
                        ->except(['index']);
                });
            });
        });
    });
});
