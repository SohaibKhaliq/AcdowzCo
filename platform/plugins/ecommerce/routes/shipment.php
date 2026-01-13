<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Ecommerce\Http\Controllers\PrintShippingLabelController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers', 'prefix' => 'ecommerce', 'as' => 'ecommerce.'], function (): void {
        Route::group(['prefix' => 'shipments', 'as' => 'shipments.'], function (): void {
            Route::resource('', 'ShipmentController')
                ->parameters(['' => 'shipment'])
                ->except(['create', 'store']);

            Route::group(['permission' => 'ecommerce.shipments.edit'], function (): void {
                Route::get('shipments/{shipment}/print', [PrintShippingLabelController::class, '__invoke'])
                    ->name('print');

                Route::post('update-status/{shipment}', [
                    'as' => 'update-status',
                    'uses' => 'ShipmentController@postUpdateStatus',
                ])->wherePrimaryKey();

                Route::post('update-cod-status/{shipment}', [
                    'as' => 'update-cod-status',
                    'uses' => 'ShipmentController@postUpdateCodStatus',
                ])->wherePrimaryKey();
            });
        });
    });
});
