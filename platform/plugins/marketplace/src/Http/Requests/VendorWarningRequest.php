<?php

namespace Botble\Marketplace\Http\Requests;

use Botble\Marketplace\Enums\WarningLevelEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class VendorWarningRequest extends Request
{
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'exists:mp_stores,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'severity' => ['required', Rule::in(WarningLevelEnum::values())],
            'send_email' => ['nullable', 'boolean'],
        ];
    }
}
