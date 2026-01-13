<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreateProductWhenCreatingOrderRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'price' => [
                'numeric',
                'nullable',
                'min:0',
            ],
            'quantity' => ['numeric', 'nullable', 'min:0', 'max:100000000'],
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
