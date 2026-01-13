<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Support\Http\Requests\Request;

class CategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'categories' => ['nullable', 'array'],
            'categories.*' => ['nullable', 'exists:ec_product_categories,id'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'categories.*' => trans('plugins/ecommerce::products.form.categories'),
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'categories' => [
                'description' => 'Array of category IDs to filter products by',
                'example' => [1, 2, 3],
            ],
            'is_featured' => [
                'description' => 'Filter by featured status',
                'example' => true,
            ],
        ];
    }
}
