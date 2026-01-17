<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class OtpLoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ];
    }
}
