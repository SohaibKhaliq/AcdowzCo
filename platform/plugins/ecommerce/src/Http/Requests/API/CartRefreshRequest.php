<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Ecommerce\Models\Product;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CartRefreshRequest extends Request
{
    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.product_id' => [
                'required',
                'integer',
                Rule::exists(Product::class, 'id'),
            ],
            'products.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'products' => [
                'description' => 'Array of products to refresh in the cart',
                'example' => [
                    [
                        'product_id' => 1,
                        'quantity' => 2,
                    ],
                    [
                        'product_id' => 3,
                        'quantity' => 1,
                    ],
                ],
            ],
            'products.*.product_id' => [
                'description' => 'The ID of the product',
                'example' => 1,
            ],
            'products.*.quantity' => [
                'description' => 'The quantity of the product',
                'example' => 2,
            ],
        ];
    }
}
