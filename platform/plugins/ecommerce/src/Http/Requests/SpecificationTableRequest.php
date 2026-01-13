<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Models\SpecificationGroup;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SpecificationTableRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'description' => ['nullable', 'string', 'max:400'],
            'groups' => ['nullable', 'array'],
            'groups.*' => [Rule::exists(SpecificationGroup::class, 'id')],
            'order' => ['nullable', 'array'],
            'order.*' => ['integer'],
        ];
    }
}
