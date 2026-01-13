<?php

namespace Botble\Ecommerce\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FacebookPixelIdRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        if (! preg_match('/^\d{15,16}$/', $value)) {
            $fail(trans('plugins/ecommerce::setting.tracking.validation.facebook_pixel_id_format'));
        }
    }
}
