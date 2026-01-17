<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Botble\Ecommerce\Http\Controllers\Customers',
    'middleware' => ['web', 'core'],
    'as' => 'customer.otp.',
], function () {
    Route::post('otp/send', [
        'as' => 'send',
        'uses' => 'OtpLoginController@sendOtp'
    ]);
    Route::post('otp/verify', [
        'as' => 'verify',
        'uses' => 'OtpLoginController@verifyOtp'
    ]);
});
