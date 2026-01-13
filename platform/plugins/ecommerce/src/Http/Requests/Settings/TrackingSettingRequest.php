<?php

namespace Botble\Ecommerce\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Ecommerce\Rules\FacebookPixelIdRule;
use Botble\Support\Http\Requests\Request;

class TrackingSettingRequest extends Request
{
    public function rules(): array
    {
        $onOffRule = new OnOffRule();

        $rules = [
            'facebook_pixel_enabled' => $onOffRule,
            'google_tag_manager_enabled' => $onOffRule,
            'facebook_pixel_debug_mode' => $onOffRule,
        ];

        if ($this->input('facebook_pixel_enabled')) {
            $rules['facebook_pixel_id'] = ['required', 'string', 'max:120', new FacebookPixelIdRule()];
        } else {
            $rules['facebook_pixel_id'] = ['nullable', 'string', 'max:120'];
        }

        return $rules;
    }
}
