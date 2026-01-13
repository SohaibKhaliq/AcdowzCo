<?php

namespace Botble\Theme\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GoogleTrackingIdRule implements ValidationRule
{
    public function __construct(
        protected string $type = 'any'
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $patterns = [
            'gtm' => '/^GTM-[A-Z0-9]{5,}$/',
            'ga4' => '/^G-[A-Z0-9]{10,}$/',
            'ua' => '/^UA-\d{4,}-\d{1,}$/',
        ];

        $valid = false;

        switch ($this->type) {
            case 'gtm':
                $valid = preg_match($patterns['gtm'], $value);
                if (! $valid) {
                    $fail(trans('packages/theme::theme.validation.gtm_container_id_format'));
                }

                break;

            case 'ga4':
                $valid = preg_match($patterns['ga4'], $value) || preg_match($patterns['ua'], $value);
                if (! $valid) {
                    $fail(trans('packages/theme::theme.validation.google_tag_id_format'));
                }

                break;

            case 'any':
            default:
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $valid = true;

                        break;
                    }
                }
                if (! $valid) {
                    $fail(trans('packages/theme::theme.validation.google_tracking_id_format'));
                }

                break;
        }
    }
}
