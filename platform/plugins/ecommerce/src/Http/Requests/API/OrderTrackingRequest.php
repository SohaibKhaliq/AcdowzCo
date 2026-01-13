<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Support\Http\Requests\Request;

class OrderTrackingRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'code' => ['required', 'string', 'min:1'],
            'email' => ['nullable', new EmailRule()],
        ];

        if (EcommerceHelper::isOrderTrackingUsingPhone()) {
            $rules['phone'] = ['nullable', ...BaseHelper::getPhoneValidationRule(true)];
        }

        // Either email or phone must be provided
        if (EcommerceHelper::isOrderTrackingUsingPhone()) {
            $rules['phone'][] = 'required_without:email';
            $rules['email'][] = 'required_without:phone';
        } else {
            $rules['email'][] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'code.required' => __('Order code is required'),
            'email.required' => __('Email is required'),
            'phone.required' => __('Phone is required'),
            'email.required_without' => __('Either email or phone is required'),
            'phone.required_without' => __('Either email or phone is required'),
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'code' => [
                'description' => 'The code of the order to track',
                'example' => 'ORD-12345',
            ],
            'email' => [
                'description' => 'The email address associated with the order (required if phone not provided)',
                'example' => 'john.doe@example.com',
            ],
            'phone' => [
                'description' => 'The phone number associated with the order (required if email not provided)',
                'example' => '0123456789',
            ],
        ];
    }
}
