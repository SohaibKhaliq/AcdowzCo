<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cart_items' => ['required', 'array'],
            'cart_items.*.id' => ['required', 'exists:products,id'],
            'cart_items.*.quantity' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['required', 'array'],
            'shipping_address.name' => ['required', 'string'],
            'shipping_address.address' => ['required', 'string'],
            'shipping_address.city' => ['required', 'string'],
            'shipping_address.country' => ['required', 'string'],
            'shipping_address.postcode' => ['required', 'string'],
            'payment_method' => ['required', 'string', 'in:credit_card,paypal,bank_transfer'], // Thêm các phương thức thanh toán khác nếu cần
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'cart_items' => [
                'description' => 'Array of items in the cart',
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
            'cart_items.*.id' => [
                'description' => 'The ID of the product',
                'example' => 1,
            ],
            'cart_items.*.quantity' => [
                'description' => 'The quantity of the product',
                'example' => 2,
            ],
            'shipping_address' => [
                'description' => 'The shipping address information',
                'example' => [
                    'name' => 'John Doe',
                    'address' => '123 Main St',
                    'city' => 'Los Angeles',
                    'country' => 'United States',
                    'postcode' => '90001',
                ],
            ],
            'shipping_address.name' => [
                'description' => 'The name of the recipient',
                'example' => 'John Doe',
            ],
            'shipping_address.address' => [
                'description' => 'The street address',
                'example' => '123 Main St',
            ],
            'shipping_address.city' => [
                'description' => 'The city',
                'example' => 'Los Angeles',
            ],
            'shipping_address.country' => [
                'description' => 'The country',
                'example' => 'United States',
            ],
            'shipping_address.postcode' => [
                'description' => 'The postal/zip code',
                'example' => '90001',
            ],
            'payment_method' => [
                'description' => 'The payment method',
                'example' => 'credit_card',
            ],
        ];
    }
}
