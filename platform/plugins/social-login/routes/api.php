<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'api/v1/auth',
    'namespace' => 'Botble\SocialLogin\Http\Controllers\API',
], function (): void {
    Route::post('apple', 'AppleLoginController@login');
    Route::post('google', 'GoogleLoginController@login');
    Route::post('facebook', 'FacebookLoginController@login');
    Route::post('x', 'XLoginController@login');
});
