<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Models\Address;
use Botble\Support\Http\Requests\Request;

class UpdateAddressRequest extends Request
{
    public function authorize(): bool
    {
        $address = Address::query()->findOrFail($this->route('id'));

        return $address && $address->customer_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:60'],
            'phone' => ['required', 'string', ...BaseHelper::getPhoneValidationRule(true)],
            'country' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:255'],
            'is_default' => ['boolean'],
            'zip_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The name of the customer',
                'example' => 'John Doe',
            ],
            'email' => [
                'description' => 'The email address',
                'example' => 'john.doe@example.com',
            ],
            'phone' => [
                'description' => 'The phone number',
                'example' => '0123456789',
            ],
            'country' => [
                'description' => 'The country name',
                'example' => 'United States',
            ],
            'state' => [
                'description' => 'The state or province',
                'example' => 'California',
            ],
            'city' => [
                'description' => 'The city name',
                'example' => 'Los Angeles',
            ],
            'address' => [
                'description' => 'The street address',
                'example' => '123 Main St',
            ],
            'is_default' => [
                'description' => 'Whether this is the default address',
                'example' => true,
            ],
            'zip_code' => [
                'description' => 'The postal/zip code',
                'example' => '90001',
            ],
        ];
    }

}
