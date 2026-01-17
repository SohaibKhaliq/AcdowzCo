<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Services\PhoneOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OtpController extends BaseController
{
    protected PhoneOtpService $otpService;

    public function __construct(PhoneOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp(Request $request): BaseHttpResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:10|max:20'
        ]);

        if ($validator->fails()) {
            return $this->httpResponse()
                ->setError()
                ->setMessage($validator->errors()->first());
        }

        $phone = $request->input('phone');
        $result = $this->otpService->sendOtp($phone);

        return $this->httpResponse()
            ->setError($result['error'])
            ->setMessage($result['message']);
    }

    public function verifyOtp(Request $request): BaseHttpResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:10|max:20',
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return $this->httpResponse()
                ->setError()
                ->setMessage($validator->errors()->first());
        }

        $phone = $request->input('phone');
        $otp = $request->input('otp');

        $result = $this->otpService->verifyOtp($phone, $otp);

        if (!$result['error']) {
            // Log in the customer
            $customer = \Botble\Ecommerce\Models\Customer::find($result['data']['customer_id']);
            Auth::guard('customer')->login($customer, true);
        }

        return $this->httpResponse()
            ->setError($result['error'])
            ->setMessage($result['message'])
            ->setData($result['data'] ?? []);
    }

    public function resendOtp(Request $request): BaseHttpResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:10|max:20'
        ]);

        if ($validator->fails()) {
            return $this->httpResponse()
                ->setError()
                ->setMessage($validator->errors()->first());
        }

        $phone = $request->input('phone');
        $result = $this->otpService->resendOtp($phone);

        return $this->httpResponse()
            ->setError($result['error'])
            ->setMessage($result['message']);
    }
}
