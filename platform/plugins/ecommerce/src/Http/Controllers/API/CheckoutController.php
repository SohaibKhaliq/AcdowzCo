<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends BaseApiController
{
    /**
     * @group Checkout
     *
     * Process Checkout
     *
     * Process the checkout for a specific cart ID. This endpoint restores the cart, generates an order token,
     * and redirects the user to the checkout page.
     *
     * @urlParam id string required The ID of the cart to process. Example: 12345
     * @authenticated
     *
     * @response 302 {}
     * @response 401 {
     *     "message": "Unauthenticated."
     * }
     * @response 404 {
     *     "message": "Cart not found."
     * }
     *
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function process(string $id, Request $request, HandleApplyCouponService $applyCouponService)
    {
        Cart::instance('cart')->destroy();

        Cart::instance('cart')->restore($id);

        // Get the cart content to check for coupon code
        $content = Cart::instance('cart')->content();
        $couponCode = null;

        // Look for the first item with a coupon_code option
        foreach ($content as $item) {
            if (isset($item->options['coupon_code']) && $item->options['coupon_code']) {
                $couponCode = $item->options['coupon_code'];

                break;
            }
        }

        $token = md5(Str::random(40));
        session(['tracked_start_checkout' => $token]);

        $user = $request->user();

        if ($user instanceof Customer) {
            Auth::guard('customer')->login($user);
        }

        // Apply coupon code if it exists
        if ($couponCode) {
            // Create a new order session
            $sessionData = OrderHelper::getOrderSessionData($token);

            // Apply the coupon code
            $result = $applyCouponService->execute($couponCode, $sessionData);

            // If there's an error applying the coupon, we'll just log it and continue
            if (isset($result['error']) && $result['error']) {
                logger()->error('Error applying coupon during checkout: ' . ($result['message'] ?? 'Unknown error'));
            } else {
                // Store the coupon code in the session for the checkout process
                session()->put('applied_coupon_code', $couponCode);
            }
        }

        Cart::instance('cart')->store($id);

        return redirect()->to(route('public.checkout.information', $token));
    }
}
