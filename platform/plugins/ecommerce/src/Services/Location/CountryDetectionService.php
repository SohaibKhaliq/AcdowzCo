<?php

namespace Botble\Ecommerce\Services\Location;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\CustomerCountry;
use Botble\Location\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

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
            if (function_exists('geoip_country_code_by_name')) {
                $ip = request()->ip();
                $isoCode = @geoip_country_code_by_name($ip);
                
                if ($isoCode) {
                    $country = Country::query()
                        ->where('iso_code', $isoCode)
                        ->where('status', 'published')
                        ->first();
                    
                    return $country?->id;
                }
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
