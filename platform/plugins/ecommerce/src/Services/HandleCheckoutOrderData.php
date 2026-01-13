<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Enums\ShippingMethodEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\ValueObjects\CheckoutOrderData;
use Botble\Payment\Supports\PaymentFeeHelper;
use Botble\Payment\Supports\PaymentHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HandleCheckoutOrderData
{
    public function __construct(
        protected HandleApplyPromotionsService $applyPromotionsService,
        protected HandleShippingFeeService $shippingFeeService,
        protected HandleApplyCouponService $applyCouponService,
        protected HandleRemoveCouponService $removeCouponService,
        protected HandleSetCountryForPaymentCheckout $setCountryForPaymentCheckout
    ) {
    }

    public function execute(Request $request, Collection $products, string $token, array &$sessionCheckoutData): CheckoutOrderData
    {
        $paymentMethod = null;

        if (is_plugin_active('payment')) {
            $paymentMethod = $request->input(
                'payment_method',
                session('selected_payment_method') ?: PaymentHelper::defaultPaymentMethod()
            );
        }

        if ($paymentMethod) {
            session()->put('selected_payment_method', $paymentMethod);
        }

        $this->setCountryForPaymentCheckout->execute($sessionCheckoutData);

        if (is_plugin_active('marketplace')) {
            [
                $sessionCheckoutData,
                $shipping,
                $defaultShippingMethod,
                $defaultShippingOption,
                $shippingAmount,
                $promotionDiscountAmount,
                $couponDiscountAmount,
            ] = apply_filters(PROCESS_CHECKOUT_ORDER_DATA_ECOMMERCE, $products, $token, $sessionCheckoutData, $request);

            foreach ($sessionCheckoutData['marketplace'] as $storeData) {
                if (! empty($storeData['created_order_id'])) {
                    $order = Order::query()
                        ->where('id', $storeData['created_order_id'])
                        ->first();

                    if (
                        $order
                        && isset($storeData['shipping_amount'])
                        && $order->shipping_amount != $storeData['shipping_amount']
                    ) {
                        $order->update(['shipping_amount' => $storeData['shipping_amount']]);
                    }
                }
            }
        } else {
            $promotionDiscountAmount = $this->applyPromotionsService->execute($token);

            $sessionCheckoutData['promotion_discount_amount'] = $promotionDiscountAmount;

            $couponDiscountAmount = 0;
            if (session()->has('applied_coupon_code')) {
                $couponDiscountAmount = Arr::get($sessionCheckoutData, 'coupon_discount_amount', 0);
            }

            $orderTotal = Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount;
            $orderTotal = max($orderTotal, 0);

            $shipping = [];

            $shippingAmount = 0;
            $defaultShippingMethod = $request->input('shipping_method') ?: Arr::get($sessionCheckoutData, 'shipping_method', ShippingMethodEnum::DEFAULT);
            $defaultShippingOption = null;

            if ($isAvailableShipping = EcommerceHelper::isAvailableShipping($products)) {
                $origin = EcommerceHelper::getOriginAddress();
                $shippingData = EcommerceHelper::getShippingData(
                    $products,
                    $sessionCheckoutData,
                    $origin,
                    $orderTotal,
                    $paymentMethod,
                );

                $shipping = $this->shippingFeeService->execute($shippingData);

                foreach ($shipping as $key => &$shipItem) {
                    if (get_shipping_setting('free_ship', $key)) {
                        foreach ($shipItem as &$subShippingItem) {
                            Arr::set($subShippingItem, 'price', 0);
                        }
                    }
                }

                if ($shipping) {
                    $defaultShippingMethod = $request->input(
                        'shipping_method',
                        Arr::get($sessionCheckoutData, 'shipping_method', ShippingMethodEnum::DEFAULT)
                    );

                    if (! $defaultShippingMethod) {
                        $defaultShippingMethod = old(
                            'shipping_method',
                            Arr::get($sessionCheckoutData, 'shipping_method', Arr::first(array_keys($shipping)))
                        );
                    }

                    $defaultShippingOption = Arr::first(array_keys(Arr::first($shipping)));

                    if ($optionRequest = $request->input('shipping_option', old('shipping_option'))) {
                        if (
                            (is_string($optionRequest) || is_int($optionRequest))
                            && array_key_exists($optionRequest, Arr::get($shipping, $defaultShippingMethod, []))
                        ) {
                            $defaultShippingOption = $optionRequest;
                        }
                    } else {
                        $defaultShippingOptionFromSession = Arr::get(
                            $sessionCheckoutData,
                            'shipping_option',
                            $defaultShippingOption
                        );

                        if (isset($shipping[$defaultShippingMethod][$defaultShippingOptionFromSession])) {
                            $defaultShippingOption = $defaultShippingOptionFromSession;
                        }
                    }

                    $shippingAmount = Arr::get(
                        $shipping,
                        "$defaultShippingMethod.$defaultShippingOption.price",
                        0
                    );
                }

                Arr::set($sessionCheckoutData, 'shipping_method', $defaultShippingMethod);
                Arr::set($sessionCheckoutData, 'shipping_option', $defaultShippingOption);
                Arr::set($sessionCheckoutData, 'shipping_amount', $shippingAmount);

                if (! empty($sessionCheckoutData['created_order_id'])) {
                    $order = Order::query()
                        ->where('id', $sessionCheckoutData['created_order_id'])
                        ->first();

                    if ($order && $order->shipping_amount != $shippingAmount) {
                        $order->update(['shipping_amount' => $shippingAmount]);
                    }
                }

                OrderHelper::setOrderSessionData($token, $sessionCheckoutData);
            }

            if (session()->has('applied_coupon_code')) {
                if (! $request->input('applied_coupon')) {
                    $discount = $this->applyCouponService->getCouponData(
                        session('applied_coupon_code'),
                        $sessionCheckoutData
                    );
                    if (empty($discount)) {
                        $this->removeCouponService->execute();
                    } else {
                        $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
                    }
                } else {
                    $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
                }
            }

            $sessionCheckoutData['is_available_shipping'] = $isAvailableShipping;

            if (! $sessionCheckoutData['is_available_shipping']) {
                $shippingAmount = 0;
            }
        }

        $rawTotal = Cart::instance('cart')->rawTotal();
        $orderAmount = max($rawTotal - $promotionDiscountAmount - $couponDiscountAmount, 0);
        $orderAmount += (float) $shippingAmount;

        // Add payment fee if applicable
        $paymentFee = 0;
        if ($paymentMethod && is_plugin_active('payment')) {
            $paymentFee = PaymentFeeHelper::calculateFee($paymentMethod, $orderAmount);
            $orderAmount += $paymentFee;
        }

        // Store payment fee in session
        Arr::set($sessionCheckoutData, 'payment_fee', $paymentFee);

        return new CheckoutOrderData(
            shipping: $shipping,
            sessionCheckoutData: $sessionCheckoutData,
            shippingAmount: $shippingAmount,
            rawTotal: $rawTotal,
            orderAmount: $orderAmount,
            promotionDiscountAmount: $promotionDiscountAmount,
            couponDiscountAmount: $couponDiscountAmount,
            defaultShippingMethod: $defaultShippingMethod,
            defaultShippingOption: $defaultShippingOption,
            paymentFee: $paymentFee
        );
    }
}
