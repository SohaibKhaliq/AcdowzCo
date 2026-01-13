<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Support\Http\Requests\Request;

class ReviewRequest extends Request
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:ec_products,id'],
            'star' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:5000'],
            'images' => 'array|max:' . EcommerceHelper::reviewMaxFileNumber(),
            'images.*' => 'image|mimes:jpg,jpeg,png|max:' . EcommerceHelper::reviewMaxFileSize(true),
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'product_id' => [
                'description' => 'The ID of the product to review',
                'example' => 1,
            ],
            'star' => [
                'description' => 'The rating from 1 to 5 stars',
                'example' => 5,
            ],
            'comment' => [
                'description' => 'The review comment',
                'example' => 'This is a great product! I highly recommend it.',
            ],
            'images' => [
                'description' => 'Array of images for the review (optional)',
                'example' => null,
            ],
        ];
    }
}
