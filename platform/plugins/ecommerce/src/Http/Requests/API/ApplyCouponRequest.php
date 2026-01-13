<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Support\Http\Requests\Request;

class ApplyCouponRequest extends Request
{
    public function rules(): array
    {
        return [
            'coupon_code' => ['required', 'string', 'min:3', 'max:20'],
            'cart_id' => ['required', 'string'],
        ];
    }

    /**
     * Get the body parameters for API documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'coupon_code' => [
                'description' => 'The coupon code to apply',
                'example' => 'SUMMER2023',
            ],
            'cart_id' => [
                'description' => 'The ID of the cart to apply the coupon to',
                'example' => 'e5a9bf3d-9f2c-4a1b-b73f-917f9b9d9a7c',
            ],
        ];
    }
}
