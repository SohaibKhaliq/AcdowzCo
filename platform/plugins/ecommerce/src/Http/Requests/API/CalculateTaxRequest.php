<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CalculateTaxRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:ec_products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'country' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'zip_code' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function bodyParameters(): array
    {
        return [
            'products' => [
                'description' => 'Array of products to calculate tax for',
                'example' => [
                    [
                        'id' => 1,
                        'quantity' => 2,
                    ],
                    [
                        'id' => 3,
                        'quantity' => 1,
                    ],
                ],
            ],
            'products.*.id' => [
                'description' => 'The ID of the product',
                'example' => 1,
            ],
            'products.*.quantity' => [
                'description' => 'The quantity of the product',
                'example' => 2,
            ],
            'country' => [
                'description' => 'The country for tax calculation',
                'example' => 'United States',
            ],
            'state' => [
                'description' => 'The state/province for tax calculation',
                'example' => 'California',
            ],
            'city' => [
                'description' => 'The city for tax calculation',
                'example' => 'Los Angeles',
            ],
            'zip_code' => [
                'description' => 'The postal/zip code for tax calculation',
                'example' => '90001',
            ],
        ];
    }
}
