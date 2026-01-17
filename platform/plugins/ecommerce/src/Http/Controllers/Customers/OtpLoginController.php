<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Services\OtpService;
use Botble\Ecommerce\Http\Requests\OtpLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpLoginController extends BaseController
{
    public function __construct(protected OtpService $otpService)
    {
    }

    public function sendOtp(Request $request): BaseHttpResponse
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $this->otpService->generate($request->input('phone'));

        return $this->httpResponse()
            ->setMessage(__('A verification code has been sent to your phone.'));
    }

    public function verifyOtp(OtpLoginRequest $request): BaseHttpResponse
    {
        $phone = $request->input('phone');
        $otp = $request->input('otp');

        if (!$this->otpService->verify($phone, $otp)) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Invalid or expired verification code.'));
        }

        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => 'User ' . Str::random(5),
                'phone' => $phone,
                'email' => $phone . '@placeholder.com',
                'password' => Hash::make(Str::random(32)),
                'oauth_provider' => 'phone',
                'phone_verified_at' => now(),
                'status' => 'activated',
            ]);
        }

        Auth::guard('customer')->login($customer, true);

        return $this->httpResponse()
            ->setMessage(__('Logged in successfully'))
            ->setData([
                'next_url' => session()->pull('url.intended', route('customer.overview')),
            ]);
    }
}
