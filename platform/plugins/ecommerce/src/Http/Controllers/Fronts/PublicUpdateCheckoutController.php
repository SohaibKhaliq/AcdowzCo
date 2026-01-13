<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Services\HandleCheckoutOrderData;
use Botble\Ecommerce\Services\HandleTaxService;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Facades\PaymentMethods;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PublicUpdateCheckoutController extends BaseController
{
    public function __invoke(Request $request, HandleCheckoutOrderData $handleCheckoutOrderData)
    {
        $sessionCheckoutData = OrderHelper::getOrderSessionData(
            $token = OrderHelper::getOrderSessionToken()
        );

        /**
         * @var Collection $products
         */
        $products = Cart::instance('cart')->products();

        $checkoutOrderData = $handleCheckoutOrderData->execute(
            $request,
            $products,
            $token,
            $sessionCheckoutData
        );

        app(HandleTaxService::class)->execute($products, $sessionCheckoutData);

        add_filter('payment_order_total_amount', function () use ($checkoutOrderData) {
            return $checkoutOrderData->orderAmount - $checkoutOrderData->paymentFee;
        }, 120);

        $hideCODPayment = $this->cartContainsOnlyDigitalProducts($products);

        if ($hideCODPayment) {
            PaymentMethods::excludeMethod(PaymentMethodEnum::COD);
        }

        return $this
            ->httpResponse()
            ->setData([
                'amount' => view('plugins/ecommerce::orders.partials.amount', [
                    'products' => $products,
                    'rawTotal' => $checkoutOrderData->rawTotal,
                    'orderAmount' => $checkoutOrderData->orderAmount,
                    'shipping' => $checkoutOrderData->shipping,
                    'sessionCheckoutData' => $sessionCheckoutData,
                    'shippingAmount' => $checkoutOrderData->shippingAmount,
                    'promotionDiscountAmount' => $checkoutOrderData->promotionDiscountAmount,
                    'couponDiscountAmount' => $checkoutOrderData->couponDiscountAmount,
                    'paymentFee' => $checkoutOrderData->paymentFee,
                ])->render(),
                'payment_methods' => view('plugins/ecommerce::orders.partials.payment-methods', [
                    'orderAmount' => $checkoutOrderData->orderAmount,
                ])->render(),
                'shipping_methods' => view('plugins/ecommerce::orders.partials.shipping-methods', [
                    'shipping' => $checkoutOrderData->shipping,
                    'defaultShippingOption' => $checkoutOrderData->defaultShippingOption,
                    'defaultShippingMethod' => $checkoutOrderData->defaultShippingMethod,
                ])->render(),
                'checkout_button' => view('plugins/ecommerce::orders.partials.checkout-button')->render(),
                'checkout_warnings' => apply_filters('ecommerce_checkout_form_before', '', $products),
            ]);
    }

    protected function cartContainsOnlyDigitalProducts(Collection $products): bool
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            return false;
        }

        if ($products->isEmpty()) {
            return false;
        }

        $digitalProductsCount = EcommerceHelper::countDigitalProducts($products);

        return $digitalProductsCount > 0 && $digitalProductsCount === $products->count();
    }
}
