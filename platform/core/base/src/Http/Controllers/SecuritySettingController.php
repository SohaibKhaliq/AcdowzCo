<?php

namespace Botble\Base\Http\Controllers;

use Illuminate\Contracts\View\View;

class SecuritySettingController extends BaseSystemController
{
    public function index(): View
    {
        $this->pageTitle(trans('core/setting::setting.security.title'));

        // Check current security settings
        $settings = [
            'session_http_only' => [
                'label' => trans('core/setting::setting.security.session_http_only'),
                'value' => (bool) config('session.http_only', true),
                'required' => true,
                'description' => trans('core/setting::setting.security.session_http_only_description'),
                'env_key' => 'SESSION_HTTP_ONLY',
                'recommended' => true,
            ],
            'session_secure_cookie' => [
                'label' => trans('core/setting::setting.security.session_secure_cookie'),
                'value' => (bool) config('session.secure', false),
                'required' => request()->secure(),
                'description' => trans('core/setting::setting.security.session_secure_cookie_description'),
                'env_key' => 'SESSION_SECURE_COOKIE',
                'recommended' => request()->secure(),
            ],
            'session_same_site' => [
                'label' => trans('core/setting::setting.security.session_same_site'),
                'value' => config('session.same_site', 'lax'),
                'required' => true,
                'description' => trans('core/setting::setting.security.session_same_site_description'),
                'env_key' => 'SESSION_SAME_SITE',
                'recommended' => 'lax',
            ],
            'http_security_headers' => [
                'label' => trans('core/setting::setting.security.http_security_headers'),
                'value' => (bool) config('core.base.general.enable_http_security_headers', true),
                'required' => true,
                'description' => trans('core/setting::setting.security.http_security_headers_description'),
                'env_key' => 'ENABLE_HTTP_SECURITY_HEADERS',
                'recommended' => true,
            ],
        ];

        // Check if site is using HTTPS
        $isHttps = request()->secure();

        // Check overall security status
        $allSecure = true;
        foreach ($settings as &$setting) {
            $setting['is_correct'] = $this->checkSettingStatus($setting);
            if (! $setting['is_correct'] && $setting['required']) {
                $allSecure = false;
            }
        }

        // Get .env file path for display (masked in demo mode)
        $envPath = base_path('.env');
        if (app()->environment('demo')) {
            $envPath = 'path-to-your-project/.env';
        }

        return view('core/setting::security', compact('settings', 'isHttps', 'allSecure', 'envPath'));
    }

    private function checkSettingStatus(array $setting): bool
    {
        $value = $setting['value'];
        $recommended = $setting['recommended'];

        if (is_bool($recommended)) {
            return $value === $recommended;
        }

        return $value === $recommended || $value == $recommended;
    }
}
