<?php

namespace Botble\Ecommerce\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected string $prefix = 'customer_otp_';
    protected int $expiresIn = 300; // 5 minutes

    public function generate(string $phone): string
    {
        $otp = (string) rand(100000, 999999);
        Cache::put($this->prefix . $phone, $otp, $this->expiresIn);
        
        // Log OTP for development/testing
        Log::info("OTP for $phone: $otp");
        
        // In a real scenario, integrate with SMS gateway here
        // $this->sendSms($phone, "Your verification code is: $otp");
        
        return $otp;
    }

    public function verify(string $phone, string $otp): bool
    {
        $storedOtp = Cache::get($this->prefix . $phone);
        
        if ($storedOtp && $storedOtp === $otp) {
            Cache::forget($this->prefix . $phone);
            return true;
        }
        
        return false;
    }
}
