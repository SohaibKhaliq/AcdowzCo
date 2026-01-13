<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Enums\DiscountTypeEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Http\Requests\API\ApplyCouponRequest;
use Botble\Ecommerce\Http\Resources\API\CouponResource;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleRemoveCouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CouponController extends BaseApiController
{
    public function __construct(
        protected HandleApplyCouponService $applyCouponService,
        protected HandleRemoveCouponService $removeCouponService
    ) {
    }

    /**
     * Apply coupon code
     *
     * @group Coupons
     * @param ApplyCouponRequest $request
     * @return mixed
     *
     * @bodyParam coupon_code string required The coupon code. Example: DISCOUNT20
     * @bodyParam cart_id string required ID of the cart to apply coupon to. Example: e70c6c88dae8344b03e39bb147eba66a
     */
    public function apply(ApplyCouponRequest $request)
    {
        abort_unless(EcommerceHelper::isCartEnabled(), 404);

        $cartId = $request->input('cart_id');

        if ($cartId) {
            Cart::instance('cart')->restore($cartId);
        }

        $result = [
            'error' => false,
            'message' => '',
        ];

        if (is_plugin_active('marketplace')) {
            $result = apply_filters(HANDLE_POST_APPLY_COUPON_CODE_ECOMMERCE, $result, $request);
        } else {
            $result = $this->applyCouponService->execute($request->input('coupon_code'));
        }

        if ($result['error']) {
            if ($cartId) {
                Cart::instance('cart')->store($cartId);
            }

            $response = $this
                ->httpResponse()
                ->setError()
                ->setMessage($result['message']);

            if (isset($result['error_code'])) {
                $response->setData(['error_code' => $result['error_code']]);
            }

            return $response->toApiResponse();
        }

        $couponCode = $request->input('coupon_code');

        // Store the coupon code in the cart items
        $content = Cart::instance('cart')->content();

        // First, let's create a new cart item with the coupon code
        if ($content->isNotEmpty()) {
            $firstItem = $content->first();
            $options = $firstItem->options->toArray();
            $options['coupon_code'] = $couponCode;

            // Update the first item with the coupon code
            Cart::instance('cart')->update($firstItem->rowId, [
                'options' => $options,
            ]);
        }

        // Store the cart to save changes
        if ($cartId) {
            Cart::instance('cart')->store($cartId);
        }

        // Get updated cart data
        $cartData = $this->getCartData($cartId);

        return $this
            ->httpResponse()
            ->setData($cartData)
            ->setMessage(__('plugins/ecommerce::discount.coupon_applied_successfully', ['code' => $couponCode]))
            ->toApiResponse();
    }

    /**
     * Remove coupon code
     *
     * @group Coupons
     * @param Request $request
     * @return mixed
     *
     * @bodyParam cart_id string ID of the cart to remove coupon from. Example: e70c6c88dae8344b03e39bb147eba66a
     */
    public function remove(Request $request)
    {
        abort_unless(EcommerceHelper::isCartEnabled(), 404);

        $cartId = $request->input('cart_id');
        $requestCouponCode = $request->input('coupon_code');

        // Step 1: Get the current cart content
        if ($cartId) {
            Cart::instance('cart')->restore($cartId);
        }

        $cartContent = Cart::instance('cart')->content();
        if ($cartContent->isEmpty()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Cart is empty'))
                ->toApiResponse();
        }

        // Step 2: Find the coupon code in the cart items
        $couponCode = null;
        foreach ($cartContent as $item) {
            if (isset($item->options['coupon_code']) && $item->options['coupon_code']) {
                $couponCode = $item->options['coupon_code'];

                break;
            }
        }

        // If coupon code not found in cart items, use the one from the request
        if (! $couponCode && $requestCouponCode) {
            $couponCode = $requestCouponCode;
        }

        if (! $couponCode) {
            if ($cartId) {
                Cart::instance('cart')->store($cartId);
            }

            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('No coupon code found'))
                ->toApiResponse();
        }

        // Step 3: Temporarily store the coupon code in the session
        session()->put('applied_coupon_code', $couponCode);

        // Step 4: Remove the coupon using the service
        if (is_plugin_active('marketplace')) {
            $products = Cart::instance('cart')->products();
            apply_filters(HANDLE_POST_REMOVE_COUPON_CODE_ECOMMERCE, $products, $request);
        } else {
            $this->removeCouponService->execute();
        }

        // Step 5: Remove the coupon code from all cart items
        foreach ($cartContent as $item) {
            if (isset($item->options['coupon_code'])) {
                $options = $item->options->toArray();
                unset($options['coupon_code']);

                Cart::instance('cart')->update($item->rowId, [
                    'options' => $options,
                ]);
            }
        }

        // Step 6: Store the updated cart
        if ($cartId) {
            Cart::instance('cart')->store($cartId);
        }

        // Get updated cart data
        $cartData = $this->getCartData($cartId);

        return $this
            ->httpResponse()
            ->setData($cartData)
            ->setMessage(__('plugins/ecommerce::discount.coupon_removed_successfully'))
            ->toApiResponse();
    }

    /**
     * Get all available coupons
     *
     * @group Coupons
     * @param Request $request
     * @return mixed
     *
     * @queryParam coupon_ids string Optional comma-separated list of coupon IDs to filter by. Example: 1,2,3
     */
    public function index(Request $request)
    {
        $query = Discount::query()
            ->where('type', DiscountTypeEnum::COUPON)
            ->active()
            ->available();

        // If specific coupon IDs are provided, filter by them
        if ($request->has('coupon_ids')) {
            $couponIds = array_filter(explode(',', $request->input('coupon_ids')));
            if (! empty($couponIds)) {
                $query->whereIn('id', $couponIds);
            }
        }

        $coupons = $query->get();

        return $this
            ->httpResponse()
            ->setData(CouponResource::collection($coupons))
            ->toApiResponse();
    }

    /**
     * Get cart data with applied discounts
     *
     * @param string|null $cartId
     * @return array
     */
    protected function getCartData(?string $cartId = null): array
    {
        $token = OrderHelper::getOrderSessionToken();
        $sessionData = OrderHelper::getOrderSessionData($token);

        // Get promotion discount amount from session
        $promotionDiscountAmount = Arr::get($sessionData, 'promotion_discount_amount', 0);

        $couponDiscountAmount = 0;
        $rawTotal = Cart::instance('cart')->rawTotal();
        $cartItems = Cart::instance('cart')->content();
        $countCart = Cart::instance('cart')->count();

        // Get coupon code from cart items
        $couponCode = null;
        foreach ($cartItems as $item) {
            if (isset($item->options['coupon_code']) && $item->options['coupon_code']) {
                $couponCode = $item->options['coupon_code'];

                break;
            }
        }

        // If not found in cart items, try to get from session as fallback
        if (! $couponCode) {
            $couponCode = session('applied_coupon_code');
        }

        if ($couponCode) {
            // Apply the coupon to calculate the discount
            $couponData = $this->applyCouponService->execute($couponCode, $sessionData);

            if (! Arr::get($couponData, 'error')) {
                $couponDiscountAmount = Arr::get($couponData, 'data.discount_amount', 0);
            } else {
                // If there's an error applying the coupon, log it and set discount to 0
                logger()->error('Error calculating coupon discount: ' . Arr::get($couponData, 'message', 'Unknown error'));
            }
        }

        $orderTotal = $rawTotal - $promotionDiscountAmount - $couponDiscountAmount;
        if ($orderTotal < 0) {
            $orderTotal = 0;
        }

        $cartData = [
            'cart_items' => $cartItems,
            'count' => $countCart,
            'raw_total' => $rawTotal,
            'raw_total_formatted' => format_price($rawTotal),
            'promotion_discount_amount' => $promotionDiscountAmount,
            'promotion_discount_amount_formatted' => format_price($promotionDiscountAmount),
            'coupon_discount_amount' => $couponDiscountAmount,
            'coupon_discount_amount_formatted' => format_price($couponDiscountAmount),
            'applied_coupon_code' => $couponCode,
            'order_total' => $orderTotal,
            'order_total_formatted' => format_price($orderTotal),
        ];

        if ($cartId) {
            $cartData['cart_id'] = $cartId;
        }

        return $cartData;
    }
}
