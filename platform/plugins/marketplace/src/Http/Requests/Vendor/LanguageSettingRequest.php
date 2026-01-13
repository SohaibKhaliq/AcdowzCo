<?php

namespace Botble\Marketplace\Http\Requests\Vendor;

use Botble\Base\Supports\Language;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LanguageSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'locale' => ['sometimes', 'required', Rule::in(array_keys(Language::getAvailableLocales()))],
        ];
    }
}
