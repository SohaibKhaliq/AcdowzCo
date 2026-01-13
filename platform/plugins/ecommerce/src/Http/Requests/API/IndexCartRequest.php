<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Support\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class IndexCartRequest extends Request
{
    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string'],
            'customer_id' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => __('Device ID is required!'),
            'customer_id.integer' => __('Customer ID must be a number!'),
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'device_id' => [
                'description' => 'The unique identifier of the device',
                'example' => 'e70c6c88dae8344b03e39bb147eba66a',
            ],
            'customer_id' => [
                'description' => 'The ID of the customer (optional)',
                'example' => 1,
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => __('Invalid data send'),
            'details' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
