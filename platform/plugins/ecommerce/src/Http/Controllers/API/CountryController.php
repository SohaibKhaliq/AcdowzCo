<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Facades\EcommerceHelper;

class CountryController extends BaseApiController
{
    /**
     * Get list of available countries
     *
     * @group Address
     *
     * @response {
     *   "error": false,
     *   "data": [
     *     {
     *       "name": "Vietnam",
     *       "code": "VN"
     *     }
     *   ],
     *   "message": null
     * }
     */
    public function index()
    {
        $countries = collect(EcommerceHelper::getAvailableCountries())
            ->map(function ($name, $code) {
                if (! $code) {
                    $code = '';
                }

                return [
                    'name' => $name,
                    'code' => $code,
                ];
            })
            ->values();

        return $this->httpResponse()
            ->setData($countries);
    }
}
