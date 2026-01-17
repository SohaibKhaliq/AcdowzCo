<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\CustomerOtp;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PhoneOtpService
{
    public function sendOtp(string $phone): array
    {
        try {
            // Generate 6-digit OTP
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP in cache for verification (expires based on settings)
            $expiryMinutes = get_ecommerce_setting('otp_expiry_minutes', 5);
            Cache::put("otp_phone_{$phone}", $otpCode, now()->addMinutes($expiryMinutes));

            // Store in database for tracking
            CustomerOtp::updateOrCreate(
                ['phone' => $phone],
                [
                    'otp_code' => $otpCode,
                    'expires_at' => now()->addMinutes($expiryMinutes),
                    'verified' => false
                ]
            );

            // Send OTP via SMS (integration with SMS provider)
            $this->sendSms($phone, $otpCode);

            return [
                'error' => false,
                'message' => __('OTP sent successfully to :phone', ['phone' => $phone])
            ];
        } catch (Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());

            return [
                'error' => true,
                'message' => __('Failed to send OTP. Please try again.')
            ];
        }
    }

    public function verifyOtp(string $phone, string $otpCode): array
    {
        try {
            // Check if OTP exists in cache
            $cachedOtp = Cache::get("otp_phone_{$phone}");

            if (!$cachedOtp || $cachedOtp !== $otpCode) {
                return [
                    'error' => true,
                    'message' => __('Invalid or expired OTP code')
                ];
            }

            // Mark OTP as verified
            CustomerOtp::where('phone', $phone)
                ->where('otp_code', $otpCode)
                ->update(['verified' => true]);

            // Clear OTP from cache
            Cache::forget("otp_phone_{$phone}");

            // Find or create customer
            $customer = $this->findOrCreateCustomerByPhone($phone);

            return [
                'error' => false,
                'message' => __('Phone verified successfully'),
                'data' => [
                    'customer_id' => $customer->id,
                    'next_url' => route('customer.overview')
                ]
            ];
        } catch (Exception $e) {
            Log::error('Failed to verify OTP: ' . $e->getMessage());

            return [
                'error' => true,
                'message' => __('Failed to verify OTP. Please try again.')
            ];
        }
    }

    public function findOrCreateCustomerByPhone(string $phone): Customer
    {
        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => 'Customer ' . substr($phone, -4), // Temporary name
                'phone' => $phone,
                'password' => bcrypt(\Illuminate\Support\Str::random(32)),
                'phone_verified_at' => now(),
                'status' => 'activated',
                'reseller_id' => 'RSL' . strtoupper(\Illuminate\Support\Str::random(10)),
            ]);
        } else {
            // Update phone verification status
            $customer->update([
                'phone_verified_at' => now()
            ]);
        }

        return $customer;
    }

    protected function sendSms(string $phone, string $otpCode): void
    {
        $smsProvider = config('services.sms.provider', 'log');

        switch ($smsProvider) {
            case 'twilio':
                $this->sendViaTwilio($phone, $otpCode);
                break;
            case 'nexmo':
                $this->sendViaNexmo($phone, $otpCode);
                break;
            default:
                // For development, just log the OTP
                Log::info("OTP for {$phone}: {$otpCode}");
                break;
        }
    }

    protected function sendViaTwilio(string $phone, string $otpCode): void
    {
        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');

            if (!$sid || !$token || !$from) {
                throw new Exception('Twilio credentials not configured');
            }

            $message = __('Your verification code is: :code. This code will expire in :minutes minutes.', [
                'code' => $otpCode,
                'minutes' => get_ecommerce_setting('otp_expiry_minutes', 5)
            ]);

            Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'From' => $from,
                    'To' => $phone,
                    'Body' => $message,
                ]);
        } catch (Exception $e) {
            Log::error('Twilio SMS failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function sendViaNexmo(string $phone, string $otpCode): void
    {
        try {
            $key = config('services.nexmo.key');
            $secret = config('services.nexmo.secret');
            $from = config('services.nexmo.from');

            if (!$key || !$secret || !$from) {
                throw new Exception('Nexmo credentials not configured');
            }

            $message = __('Your verification code is: :code', ['code' => $otpCode]);

            Http::post('https://rest.nexmo.com/sms/json', [
                'api_key' => $key,
                'api_secret' => $secret,
                'from' => $from,
                'to' => $phone,
                'text' => $message,
            ]);
        } catch (Exception $e) {
            Log::error('Nexmo SMS failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function resendOtp(string $phone): array
    {
        // Check rate limiting
        $lastSent = Cache::get("otp_last_sent_{$phone}");

        if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
            return [
                'error' => true,
                'message' => __('Please wait before requesting another OTP')
            ];
        }

        // Record when OTP was sent
        Cache::put("otp_last_sent_{$phone}", now(), now()->addMinutes(5));

        return $this->sendOtp($phone);
    }
}
