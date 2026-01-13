<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Enums\DiscountTypeEnum;
use Botble\Ecommerce\Enums\DiscountTypeOptionEnum;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Models\Discount;
use Illuminate\Support\Arr;

class HandleRemoveCouponService
{
    public function execute(?string $prefix = '', bool $isForget = true, ?string $couponCode = null): array
    {
        // If no coupon code is provided, try to get it from the session
        if (! $couponCode) {
            if (! session()->has('applied_coupon_code')) {
                return [
                    'error' => true,
                    'message' => trans('plugins/ecommerce::discount.not_used'),
                ];
            }

            $couponCode = session('applied_coupon_code');
        }

        // Store the coupon code in the session temporarily to ensure compatibility with other methods
        if (! session()->has('applied_coupon_code')) {
            session()->put('applied_coupon_code', $couponCode);
        }

        $discount = Discount::query()
            ->where('code', $couponCode)
            ->where('type', DiscountTypeEnum::COUPON)
            ->first();

        $token = OrderHelper::getOrderSessionToken();

        $sessionData = OrderHelper::getOrderSessionData($token);

        if ($discount && $discount->type_option == DiscountTypeOptionEnum::SHIPPING) {
            Arr::set($sessionData, $prefix . 'is_free_shipping', false);
        }

        Arr::set($sessionData, $prefix . 'coupon_discount_amount', 0);
        OrderHelper::setOrderSessionData($token, $sessionData);

        if ($isForget) {
            session()->forget('applied_coupon_code');
        }

        if (session()->has('auto_apply_coupon_code') && session('auto_apply_coupon_code') === $couponCode) {
            session()->forget('auto_apply_coupon_code');
        }

        return [
            'error' => false,
        ];
    }
}
