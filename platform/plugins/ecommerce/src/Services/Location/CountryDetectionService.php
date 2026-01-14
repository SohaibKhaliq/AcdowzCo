<?php

namespace Botble\Ecommerce\Services\Location;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\CustomerCountry;
use Botble\Location\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class CountryDetectionService
{
    protected const COUNTRY_COOKIE_NAME = 'user_country_id';
    protected const COUNTRY_SESSION_KEY = 'current_country_id';

    public function detectCountry(?Customer $customer = null): ?int
    {
        if ($customer && $customer->preferred_country_id) {
            return $customer->preferred_country_id;
        }

        if (Session::has(self::COUNTRY_SESSION_KEY)) {
            return Session::get(self::COUNTRY_SESSION_KEY);
        }

        if ($cookieCountry = Cookie::get(self::COUNTRY_COOKIE_NAME)) {
            Session::put(self::COUNTRY_SESSION_KEY, $cookieCountry);
            return (int) $cookieCountry;
        }

        if ($customer) {
            $lastCountry = $customer->countryHistory()->latest()->first();
            if ($lastCountry) {
                return $lastCountry->country_id;
            }
        }

        $ipCountry = $this->detectFromIp();
        if ($ipCountry) {
            return $ipCountry;
        }

        return $this->getDefaultCountry();
    }

    protected function detectFromIp(): ?int
    {
        try {
            // Prefer forwarded IP when behind proxies
            $ipHeader = request()->header('X-Forwarded-For') ?: request()->server('HTTP_X_FORWARDED_FOR');
            if ($ipHeader) {
                $ips = explode(',', $ipHeader);
                $ip = trim($ips[0]);
            } else {
                $ip = request()->ip();
            }

            // Ignore local or invalid addresses
            if (in_array($ip, ['127.0.0.1', '::1'], true) || !filter_var($ip, FILTER_VALIDATE_IP)) {
                return null;
            }

            $isoCode = null;

            // Use native geoip function when available
            if (function_exists('\geoip_country_code_by_name')) {
                $isoCode = @\geoip_country_code_by_name($ip);
            }

            // Fallback to public API if needed and allowed by env
            if (!$isoCode && env('GEOIP_ENABLED', true)) {
                $timeout = (int) env('GEOIP_FALLBACK_TIMEOUT', 3);
                $response = Http::timeout($timeout)->get("https://ipapi.co/{$ip}/json/");

                if ($response->ok()) {
                    $json = $response->json();
                    $isoCode = $json['country'] ?? ($json['country_code'] ?? null);
                }
            }

            if ($isoCode) {
                $country = Country::query()
                    ->where('iso_code', $isoCode)
                    ->where('status', 'published')
                    ->first();

                return $country?->id;
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return null;
    }

    public function detectFromPhone(string $phone): ?int
    {
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($phone, '+')) {
            $phoneCode = substr($phone, 1, 3);

            $country = Country::query()
                ->where('phone_code', 'like', $phoneCode . '%')
                ->where('status', 'published')
                ->first();

            if ($country) {
                return $country->id;
            }

            $phoneCode = substr($phone, 1, 2);
            $country = Country::query()
                ->where('phone_code', $phoneCode)
                ->where('status', 'published')
                ->first();

            return $country?->id;
        }

        return null;
    }

    public function setCountry(int $countryId, ?Customer $customer = null, string $detectedBy = 'manual'): void
    {
        $country = Country::query()
            ->where('id', $countryId)
            ->where('status', 'published')
            ->first();

        if (!$country) {
            return;
        }

        Session::put(self::COUNTRY_SESSION_KEY, $countryId);
        Cookie::queue(self::COUNTRY_COOKIE_NAME, $countryId, 60 * 24 * 90);

        if ($customer) {
            $customer->update(['preferred_country_id' => $countryId]);

            CustomerCountry::query()->create([
                'customer_id' => $customer->id,
                'country_id' => $countryId,
                'detected_by' => $detectedBy,
                'confirmed_at' => now(),
            ]);
        }
    }

    public function getCurrentCountryId(): ?int
    {
        $customer = Auth::guard('customer')->user();
        return $this->detectCountry($customer);
    }

    public function getDefaultCountry(): ?int
    {
        $country = Country::query()
            ->where('is_default', true)
            ->where('status', 'published')
            ->first();

        return $country?->id;
    }

    public function getCountryName(?int $countryId): ?string
    {
        if (!$countryId) {
            return null;
        }

        $country = Country::query()->find($countryId);
        return $country?->name;
    }

    public function clearCountry(): void
    {
        Session::forget(self::COUNTRY_SESSION_KEY);
        Cookie::queue(Cookie::forget(self::COUNTRY_COOKIE_NAME));
    }
}
