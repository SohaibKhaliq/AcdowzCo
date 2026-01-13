<?php

namespace Botble\Marketplace\Http\Requests\Fronts;

use Botble\Base\Rules\EmailRule;
use Botble\Base\Rules\MediaImageRule;
use Botble\Marketplace\Http\Requests\StoreRequest;
use Botble\Marketplace\Models\Store;
use Illuminate\Validation\Rule;

class VendorStoreRequest extends StoreRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        unset($rules['customer_id'], $rules['status']);

        $rules['email'] = [
            'required',
            new EmailRule(),
            Rule::unique((new Store())->getTable(), 'email')
                ->ignore(auth('customer')->user()->store?->id),
        ];

        $rules['logo_input'] = ['nullable', new MediaImageRule()];
        $rules['cover_image_input'] = ['nullable', new MediaImageRule()];

        return $rules;
    }
}
