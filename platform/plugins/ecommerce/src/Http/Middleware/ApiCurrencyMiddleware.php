<?php

namespace Botble\Ecommerce\Http\Middleware;

use Botble\Ecommerce\Facades\Currency as CurrencyFacade;
use Botble\Ecommerce\Models\Currency;
use Closure;
use Illuminate\Http\Request;

class ApiCurrencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $currencyCode = null;

        if ($request->has('currency')) {
            $currencyCode = $request->query('currency');
        } elseif ($request->header('X-CURRENCY')) {
            $currencyCode = $request->header('X-CURRENCY');
        }

        if ($currencyCode) {
            $currency = Currency::query()->where('title', $currencyCode)->first();

            if ($currency) {
                CurrencyFacade::setApplicationCurrency($currency);
            }
        }

        return $next($request);
    }
}
