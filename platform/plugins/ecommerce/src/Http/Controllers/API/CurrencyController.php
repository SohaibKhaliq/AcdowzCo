<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Facades\Currency as CurrencyFacade;
use Illuminate\Http\JsonResponse;

class CurrencyController extends BaseApiController
{
    /**
     * Get list of available currencies
     *
     * @group Currencies
     *
     * @response {
     *   "error": false,
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "USD",
     *       "symbol": "$",
     *       "is_prefix_symbol": true,
     *       "decimals": 2,
     *       "order": 0,
     *       "is_default": true,
     *       "exchange_rate": 1
     *     },
     *     {
     *       "id": 2,
     *       "title": "EUR",
     *       "symbol": "€",
     *       "is_prefix_symbol": false,
     *       "decimals": 2,
     *       "order": 1,
     *       "is_default": false,
     *       "exchange_rate": 0.91
     *     }
     *   ],
     *   "message": null
     * }
     */
    public function index()
    {
        $currencies = CurrencyFacade::currencies();

        return response()
            ->json($currencies);
    }

    /**
     * Get current currency
     *
     * @group Currencies
     *
     * @response {
     *   "error": false,
     *   "data": {
     *     "id": 1,
     *     "title": "USD",
     *     "symbol": "$",
     *     "is_prefix_symbol": true,
     *     "decimals": 2,
     *     "order": 0,
     *     "is_default": true,
     *     "exchange_rate": 1
     *   },
     *   "message": null
     * }
     */
    public function getCurrentCurrency(): JsonResponse
    {
        $currency = CurrencyFacade::getApplicationCurrency();

        return response()
            ->json($currency);
    }
}
