<?php

namespace Botble\Ads\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Validator;

class AdsSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'ads_google_adsense_mode' => ['nullable', 'string', 'in:none,auto,unit'],
            'ads_google_adsense_auto_ads' => ['nullable', 'string', 'max:1000'],
            'ads_google_adsense_unit_client_id' => ['nullable', 'string', 'max:50'],
            'ads_google_adsense_txt_file' => ['nullable', 'file', 'mimes:txt', 'max:2048'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $mode = $this->input('ads_google_adsense_mode');

            $autoAds = $this->input('ads_google_adsense_auto_ads');
            if ($autoAds) {
                $autoAds = trim($autoAds);
                if ($autoAds && ! $this->isValidAdSenseAutoAdsSnippet($autoAds)) {
                    $validator->errors()->add(
                        'ads_google_adsense_auto_ads',
                        trans('plugins/ads::ads.settings.validation.invalid_auto_ads_snippet')
                    );
                }
            }

            $unitClientId = $this->input('ads_google_adsense_unit_client_id');
            if ($unitClientId) {
                $unitClientId = trim($unitClientId);
                if ($unitClientId && ! preg_match('/^ca-pub-\d{16}$/', $unitClientId)) {
                    $validator->errors()->add(
                        'ads_google_adsense_unit_client_id',
                        trans('plugins/ads::ads.settings.validation.invalid_client_id_format')
                    );
                }
            }
        });
    }

    protected function isValidAdSenseAutoAdsSnippet(string $snippet): bool
    {
        $snippet = trim($snippet);

        $hasScriptAsync = preg_match('/<script\s+[^>]*async[^>]*>/is', $snippet);
        $hasAdSenseUrl = preg_match('/src\s*=\s*["\']https:\/\/pagead2\.googlesyndication\.com\/pagead\/js\/adsbygoogle\.js/is', $snippet);
        $hasClientId = preg_match('/client\s*=\s*["\']?ca-pub-\d{16}/is', $snippet);
        $hasCrossorigin = preg_match('/crossorigin\s*=\s*["\']?anonymous/is', $snippet);

        if (! $hasScriptAsync || ! $hasAdSenseUrl || ! $hasClientId || ! $hasCrossorigin) {
            return false;
        }

        if (preg_match('/<script[^>]*>\s*[^<\s]+.*<\/script>/is', $snippet)) {
            return false;
        }

        if (preg_match('/\b(eval|document\.write|innerHTML|outerHTML|insertAdjacentHTML)\s*\(/i', $snippet)) {
            return false;
        }

        if (preg_match('/data:[^,]*base64/i', $snippet)) {
            return false;
        }

        if (preg_match_all('/src\s*=\s*["\']([^"\']+)["\']/is', $snippet, $matches)) {
            foreach ($matches[1] as $url) {
                if (! str_contains($url, 'pagead2.googlesyndication.com')) {
                    return false;
                }
            }
        }

        $scriptCount = substr_count(strtolower($snippet), '<script');
        if ($scriptCount !== 1) {
            return false;
        }

        if (! preg_match('/<\/script>\s*$/is', $snippet)) {
            return false;
        }

        return true;
    }
}
