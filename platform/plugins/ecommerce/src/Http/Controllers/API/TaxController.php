<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Http\Requests\API\CalculateTaxRequest;
use Botble\Ecommerce\Http\Resources\API\TaxCalculationResource;
use Botble\Ecommerce\Services\Data\CalculateTaxData;
use Botble\Ecommerce\Services\TaxCalculatorService;

class TaxController extends BaseApiController
{
    public function __construct(protected TaxCalculatorService $taxCalculatorService)
    {
    }

    /**
     * Calculate tax for products in cart
     *
     * @group Cart
     *
     * @param CalculateTaxRequest $request Request with cart items data
     * @return TaxCalculationResource JSON response with calculated tax details
     *
     * @bodyParam products array required List of products with id and quantity.
     * @bodyParam products.*.id integer required Product ID. Example: 1
     * @bodyParam products.*.quantity integer required Product quantity. Example: 2
     * @bodyParam country string Country code. Example: US
     * @bodyParam state string State code. Example: CA
     * @bodyParam city string City name. Example: Los Angeles
     * @bodyParam zip_code string ZIP code. Example: 90001
     *
     * @response {
     *  "items": [
     *      {
     *          "product_id": 1,
     *          "price": 100,
     *          "price_formatted": "$100.00",
     *          "quantity": 2,
     *          "tax_rate": 10,
     *          "tax_amount": 20,
     *          "tax_amount_formatted": "$20.00",
     *          "subtotal": 200,
     *          "subtotal_formatted": "$200.00",
     *          "total": 220,
     *          "total_formatted": "$220.00"
     *      }
     *  ],
     *  "totals": {
     *      "sub_total": 200,
     *      "sub_total_formatted": "$200.00",
     *      "tax_amount": 20,
     *      "tax_amount_formatted": "$20.00",
     *      "total": 220,
     *      "total_formatted": "$220.00"
     *  }
     * }
     */
    public function __invoke(CalculateTaxRequest $request)
    {
        $result = $this->taxCalculatorService->execute(CalculateTaxData::fromRequestCalculateTaxRequest($request));

        return new TaxCalculationResource($result);
    }
}
