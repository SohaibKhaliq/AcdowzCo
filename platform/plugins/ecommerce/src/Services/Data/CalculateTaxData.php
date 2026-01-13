<?php

namespace Botble\Ecommerce\Services\Data;

use Botble\Ecommerce\Http\Requests\API\CalculateTaxRequest;

class CalculateTaxData
{
    public function __construct(
        public array $products,
        public ?string $country = null,
        public ?string $state = null,
        public ?string $city = null,
        public ?string $zipCode = null
    ) {
    }

    public static function fromRequestCalculateTaxRequest(CalculateTaxRequest $request): self
    {
        return new self(
            products: $request->input('products', []),
            country: $request->input('country'),
            state: $request->input('state'),
            city: $request->input('city'),
            zipCode: $request->input('zip_code')
        );
    }
}
