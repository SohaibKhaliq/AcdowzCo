<?php

use Botble\Mollie\Http\Controllers\MollieController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::post('mollie/payment/webhook/{token}', [MollieController::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('mollie.payment.webhook');
