<?php

namespace Botble\Marketplace\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\Base\Rules\MediaImageRule;
use Botble\Marketplace\Models\Store;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:250', 'min:2'],
            'email' => [
                'required',
                new EmailRule(),
                Rule::unique((new Store())->getTable(), 'email')
                    ->ignore($this->route('store.id')),
            ],
            'phone' => 'required|' . BaseHelper::getPhoneValidationRule(),
            'slug' => ['required', 'string', 'max:255'],
            'customer_id' => ['required', 'string', 'exists:ec_customers,id'],
            'description' => ['nullable', 'max:400', 'string'],
            'status' => Rule::in(BaseStatusEnum::values()),
            'company' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', ...BaseHelper::getZipcodeValidationRule(true)],
            'logo' => ['nullable', 'string', new MediaImageRule()],
            'logo_square' => ['nullable', 'string', new MediaImageRule()],
            'cover_image' => ['nullable', 'string', new MediaImageRule()],
        ];
    }
}
