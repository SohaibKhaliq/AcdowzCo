<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ResellerClick;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BuyNowController extends BaseController
{
    public function buyNow(Request $request, int $productId)
    {
        abort_unless(EcommerceHelper::isCartEnabled(), 404);

        $product = Product::findOrFail($productId);

        // Check if product is available
        if ($product->stock_status === 'out_of_stock' && !$product->allow_checkout_when_out_of_stock) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Product is out of stock'))
                ->setNextUrl($product->url);
        }

        $quantity = $request->input('quantity', 1);
        $variationId = $request->input('variation_id');

        // Create a temporary buy-now session token
        $buyNowToken = 'buy_now_' . Str::random(32);
        session()->put('buy_now_token', $buyNowToken);

        // Clear existing cart for buy now flow
        Cart::instance('cart')->destroy();

        // Add product to cart
        $cartItem = [
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $quantity,
            'price' => $product->front_sale_price,
            'options' => [
                'image' => $product->image,
                'attributes' => $request->input('attributes', []),
                'variation_id' => $variationId,
                'is_buy_now' => true,
            ],
        ];

        if ($variationId) {
            $variation = ProductVariation::find($variationId);
            if ($variation) {
                $cartItem['price'] = $variation->product->front_sale_price;
            }
        }

        Cart::instance('cart')->add($cartItem);

        // Track reseller click if referral exists
        $referralCode = $request->input('ref') ?? session('referral_code');
        if ($referralCode) {
            $this->trackResellerActivity($referralCode, $product->id, $request);
            session()->put('referral_code', $referralCode);
        }

        // Generate checkout token
        $token = OrderHelper::getOrderSessionToken();

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.checkout.information', $token));
    }

    protected function trackResellerActivity(string $referralCode, int $productId, Request $request): void
    {
        $reseller = \Botble\Ecommerce\Models\Customer::where('reseller_id', $referralCode)
            ->where('is_reseller_active', true)
            ->first();

        if ($reseller) {
            ResellerClick::create([
                'reseller_id' => $reseller->id,
                'product_id' => $productId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer_url' => $request->headers->get('referer'),
                'clicked_at' => now(),
            ]);
        }
    }
}
