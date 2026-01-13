<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Ecommerce\AdsTracking\FacebookPixel;
use Botble\Ecommerce\AdsTracking\GoogleTagManager;
use Botble\Ecommerce\Enums\DiscountTypeEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Http\Requests\API\AddCartRequest;
use Botble\Ecommerce\Http\Requests\API\CartRefreshRequest;
use Botble\Ecommerce\Http\Requests\API\DeleteCartRequest;
use Botble\Ecommerce\Http\Requests\API\UpdateCartRequest;
use Botble\Ecommerce\Http\Resources\API\CartItemResource;
use Botble\Ecommerce\Http\Resources\API\ProductCartResource;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleApplyPromotionsService;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Throwable;

class CartController extends BaseApiController
{
    public function __construct(
        protected HandleApplyPromotionsService $applyPromotionsService,
        protected HandleApplyCouponService $handleApplyCouponService
    ) {
    }

    /**
     * Get a cart item by id.
     *
     * @group Cart
     * @boyyParam device_id string required ID of the device. Example: 12345xyu-xyz
     * @bodyParam customer_id integer is ID of the customer. Example: 1
     *
     * @bodyParam id string required ID of the cart item. Example: e70c6c88dae8344b03e39bb147eba66a
     *
     * @return JsonResponse
     */
    public function index(string $id)
    {
        $identifier = $id;

        Cart::instance('cart')->restore($identifier);

        Cart::instance('cart')->storeOrIgnore($identifier);

        return response()->json([
            'id' => $identifier,
            ...$this->getDataForResponse(),
        ]);
    }

    /**
     * Add product to cart
     *
     * @group Cart
     * @param AddCartRequest $request
     * @param string|null $id Optional cart ID to add product to existing cart
     * @return JsonResponse
     * @bodyParam product_id integer required ID of the product. Example: 1
     * @bodyParam qty integer required Quantity of the product. Default: 1. Example: 1
     */
    public function store(AddCartRequest $request, ?string $id = null)
    {
        $response = $this->httpResponse();

        /**
         * @var Product $product
         */
        $product = Product::query()->find($request->input('product_id'));

        if ($product->variations->count() > 0 && ! $product->is_variation) {
            $product = $product->defaultVariation->product;
        }

        $originalProduct = $product->original_product;

        if ($product->isOutOfStock()) {
            return $response
                ->setError()
                ->setMessage(
                    __(
                        'Product :product is out of stock!',
                        ['product' => $originalProduct->name ?: $product->name]
                    )
                )
                ->toApiResponse();
        }

        $maxQuantity = $product->quantity;

        if (! $product->canAddToCart($request->input('qty', 1))) {
            return $response
                ->setError()
                ->setMessage(__('Maximum quantity is :max!', ['max' => $maxQuantity]))
                ->toApiResponse();
        }

        $outOfQuantity = false;

        // Use the provided cart ID or generate a new one
        $identifier = $id ?: (string) Str::uuid();

        Cart::instance('cart')->restore($identifier);

        foreach (Cart::instance('cart')->content() as $item) {
            if ($item->id == $product->id) {
                $originalQuantity = $product->quantity;
                $product->quantity = (int) $product->quantity - $item->qty;

                if ($product->quantity < 0) {
                    $product->quantity = 0;
                }

                if ($product->isOutOfStock()) {
                    $outOfQuantity = true;

                    break;
                }

                $product->quantity = $originalQuantity;
            }
        }

        $product->quantity = (int) $product->quantity - $request->integer('qty', 1);

        if (
            EcommerceHelper::isEnabledProductOptions() &&
            $originalProduct->options()->where('required', true)->exists()
        ) {
            if (! $request->input('options')) {
                Cart::instance('cart')->store($identifier);

                return $response
                    ->setError()
                    ->setData(['next_url' => $originalProduct->url])
                    ->setMessage(__('Please select product options!'))
                    ->toApiResponse();
            }

            $requiredOptions = $originalProduct->options()->where('required', true)->get();

            $message = null;

            foreach ($requiredOptions as $requiredOption) {
                if (! $request->input('options.' . $requiredOption->id . '.values')) {
                    $message .= trans(
                        'plugins/ecommerce::product-option.add_to_cart_value_required',
                        ['value' => $requiredOption->name]
                    );
                }
            }

            if ($message) {
                Cart::instance('cart')->store($identifier);

                return $response
                    ->setError()
                    ->setMessage(__('Please select product options!'))
                    ->toApiResponse();
            }
        }

        if ($outOfQuantity) {
            Cart::instance('cart')->store($identifier);

            return $response
                ->setError()
                ->setMessage(__(
                    'Product :product is out of stock!',
                    ['product' => $originalProduct->name ?: $product->name]
                ))
                ->toApiResponse();
        }

        $cartItems = OrderHelper::handleAddCart($product, $request);

        $cartItem = Arr::first(array_filter($cartItems, fn ($item) => $item['id'] == $product->id));

        $responseData = [
            'status' => true,
            'content' => $cartItems,
        ];

        app(GoogleTagManager::class)->addToCart(
            $originalProduct,
            $cartItem['qty'],
            $cartItem['subtotal'],
        );

        app(FacebookPixel::class)->addToCart(
            $originalProduct,
            $cartItem['qty'],
            $cartItem['subtotal'],
        );

        Cart::instance('cart')->store($identifier);

        return response()->json([
            'id' => $identifier,
            ...$this->getDataForResponse(),
            ...$responseData,
        ]);
    }

    /**
     * Update quantity of a product in cart
     *
     * @group Cart
     * @param UpdateCartRequest $request
     * @bodyParam product_id integer required ID of the product. Example: 1
     * @bodyParam qty integer required Quantity of the product. Example: 1
     *
     * @param string $id The ID of the cart to be updated.
     *
     * @return JsonResponse
     */
    public function update(UpdateCartRequest $request, string $id)
    {
        $newQty = $request->input('qty', 1);

        $productId = $request->input('product_id');

        /**
         * @var Product $product
         */
        $product = Product::query()->find($productId);

        $rowId = null;

        $identifier = $id;

        Cart::instance('cart')->restore($identifier);

        foreach (Cart::instance('cart')->content() as $item) {
            if ($item->id == $productId) {
                $rowId = $item->rowId;

                break;
            }
        }

        if (! $rowId) {
            $originalProduct = $product->original_product;

            $cartItems = OrderHelper::handleAddCart($product, $request);

            $cartItem = Arr::first(array_filter($cartItems, fn ($item) => $item['id'] == $product->id));

            $responseData = [
                'status' => true,
                'content' => $cartItems,
            ];

            app(GoogleTagManager::class)->addToCart(
                $originalProduct,
                $cartItem['qty'],
                $cartItem['subtotal'],
            );

            app(FacebookPixel::class)->addToCart(
                $originalProduct,
                $cartItem['qty'],
                $cartItem['subtotal'],
            );

            Cart::instance('cart')->store($identifier);

            return response()->json([
                'id' => $identifier,
                ...$this->getDataForResponse(),
                ...$responseData,
            ]);
        }

        $cartItem = Cart::instance('cart')->get($rowId);

        if (! $cartItem) {
            Cart::instance('cart')->store($identifier);

            return response()->json(['error' => __('Cart item not found')], 404);
        }

        /**
         * @var Product $product
         */
        $product = Product::query()->find($cartItem->id);

        if ($product) {
            $originalQuantity = $product->quantity;
            $product->quantity = (int) $product->quantity - (int) $newQty + 1;

            if ($product->quantity < 0) {
                $product->quantity = 0;
            }

            if ($product->isOutOfStock()) {
                Cart::instance('cart')->store($identifier);

                return response()->json(['error' => __('Product is out of stock')], 400);
            }

            Cart::instance('cart')->update($rowId, ['qty' => $newQty]);

            $product->quantity = $originalQuantity;
        }

        Cart::instance('cart')->store($identifier);

        return response()->json([
            'id' => $identifier,
            ...$this->getDataForResponse(),
        ]);
    }

    /**
     * Remove a cart item by its ID.
     *
     * @group Cart
     *
     * @param DeleteCartRequest $request
     *
     * @param string $id The ID of the cart to be removed.
     * @return JsonResponse Returns a JSON response with the operation status.
     */
    public function destroy(DeleteCartRequest $request, string $id)
    {
        $identifier = $id;

        $productId = $request->input('product_id');

        Cart::instance('cart')->restore($identifier);

        $rowId = null;

        foreach (Cart::instance('cart')->content() as $item) {
            if ($item->id == $productId) {
                $rowId = $item->rowId;

                break;
            }
        }

        if (! $rowId) {
            Cart::instance('cart')->store($identifier);

            return response()->json(['error' => __('Cart item not found')], 404);
        }

        try {
            $cartItem = Cart::instance('cart')->get($rowId);
            app(GoogleTagManager::class)->removeFromCart($cartItem);

            Cart::instance('cart')->remove($rowId);

            Cart::instance('cart')->store($identifier);

            return response()->json(__('Cart item removed successfully'));

        } catch (Throwable) {
            Cart::instance('cart')->store($identifier);

            return response()->json(['error' => __('Cart item not found')], 404);
        }
    }

    /**
     * Refresh cart items
     *
     * @group Cart
     * @param CartRefreshRequest $request
     * @bodyParam products array required List of products with product_id and quantity.
     * @bodyParam products.*.product_id integer required ID of the product. Example: 1
     * @bodyParam products.*.quantity integer required Quantity of the product. Example: 1
     *
     * @return JsonResponse
     */
    public function refresh(CartRefreshRequest $request)
    {
        $products = Product::query()
            ->whereIn('id', collect($request->input('products'))->pluck('product_id'))
            ->get();

        $cartTotal = 0;

        $outOfStockProducts = collect();
        foreach ($request->input('products') as $item) {
            /**
             * @var Product $product
             */
            $product = $products->firstWhere('id', $item['product_id']);
            if (! $this->validateStock($product, $item['quantity'])) {
                $outOfStockProducts->push($product);
            } else {
                $cartTotal += $product->price()->getPrice() * $item['quantity'];
            }
        }

        return $this
            ->httpResponse()
            ->setData(ProductCartResource::collection($products))
            ->setAdditional([
                'out_of_stock_products' => ProductCartResource::collection($outOfStockProducts),
                'cart_total' => $cartTotal,
                'cart_total_formatted' => format_price($cartTotal),
            ])
            ->toApiResponse();
    }

    protected function validateStock($product, $quantity = null): bool
    {
        if (! $product) {
            return false;
        }

        if ($quantity === null) {
            return ! $product->isOutOfStock();
        }

        if ($product->isOutOfStock() || $product->quantity < $quantity) {
            return false;
        }

        return true;
    }

    protected function getCartData(): array
    {
        $products = Cart::instance('cart')->products();
        $content = Cart::instance('cart')->content();

        $promotionDiscountAmount = $this->applyPromotionsService->execute();

        $couponDiscountAmount = 0;
        $couponCode = null;

        // Get coupon code from cart items
        foreach ($content as $item) {
            if (isset($item->options['coupon_code']) && $item->options['coupon_code']) {
                $couponCode = $item->options['coupon_code'];

                break;
            }
        }

        // If not found in cart items, try auto-apply coupon from URL as fallback
        if (! $couponCode && ($autoApplyCouponCode = session('auto_apply_coupon_code'))) {
            $coupon = Discount::query()
                ->where('code', $autoApplyCouponCode)
                ->where('apply_via_url', true)
                ->where('type', DiscountTypeEnum::COUPON)
                ->exists();

            if ($coupon) {
                $couponData = $this->handleApplyCouponService->execute($autoApplyCouponCode);

                if (! Arr::get($couponData, 'error')) {
                    $couponCode = $autoApplyCouponCode;
                    $couponDiscountAmount = Arr::get($couponData, 'data.discount_amount');

                    // Store the coupon code in the first cart item
                    if ($content->isNotEmpty()) {
                        $firstItem = $content->first();
                        $options = $firstItem->options->toArray();
                        $options['coupon_code'] = $couponCode;

                        // Update the first item with the coupon code
                        Cart::instance('cart')->update($firstItem->rowId, [
                            'options' => $options,
                        ]);
                    }
                }
            }
        }

        $sessionData = OrderHelper::getOrderSessionData();

        // If we have a coupon code, apply it to get the discount amount
        if ($couponCode) {
            // Apply the coupon to calculate the discount
            $couponData = $this->handleApplyCouponService->execute($couponCode, $sessionData);

            if (! Arr::get($couponData, 'error')) {
                $couponDiscountAmount = Arr::get($couponData, 'data.discount_amount', 0);
            } else {
                // If there's an error applying the coupon, log it and set discount to 0
                logger()->error('Error calculating coupon discount: ' . Arr::get($couponData, 'message', 'Unknown error'));
                $couponDiscountAmount = 0;
            }
        }

        return [$products, $promotionDiscountAmount, $couponDiscountAmount, $couponCode];
    }

    protected function getDataForResponse(): array
    {
        [$products, $promotionDiscountAmount, $couponDiscountAmount, $couponCode] = $this->getCartData();

        $cart = Cart::instance('cart');

        $content = $cart->content();
        $rawSubTotal = $cart->rawSubTotal();
        $rawTotal = $cart->rawTotal();
        $rawTaxTotal = $cart->rawTax();
        $countCart = $cart->count();

        if (is_plugin_active('marketplace')) {
            foreach ($content as $item) {
                $product = Product::query()->find($item->id);

                if (! $product) {
                    continue;
                }

                $item->options['image'] = RvMedia::getImageUrl($item->options['image']);

                if ($store = $product->original_product->store) {
                    $item->options['store'] = [
                        'id' => $store?->id,
                        'slug' => $store?->slugable?->key,
                        'name' => $store?->name,
                    ];
                }
            }
        }

        $orderTotal = $rawTotal - $promotionDiscountAmount - $couponDiscountAmount;
        if ($orderTotal < 0) {
            $orderTotal = 0;
        }

        $cartData = [
            'cart_items' => CartItemResource::collection($content),
            'count' => $countCart,
            'raw_total' => $rawTotal,
            'raw_total_formatted' => format_price($rawTotal),
            'sub_total' => $rawSubTotal,
            'sub_total_formatted' => format_price($rawSubTotal),
            'tax_amount' => $rawTaxTotal,
            'tax_amount_formatted' => format_price($rawTaxTotal),
            'promotion_discount_amount' => $promotionDiscountAmount,
            'promotion_discount_amount_formatted' => format_price($promotionDiscountAmount),
            'coupon_discount_amount' => $couponDiscountAmount,
            'coupon_discount_amount_formatted' => format_price($couponDiscountAmount),
            'applied_coupon_code' => $couponCode,
            'order_total' => $orderTotal,
            'order_total_formatted' => format_price($orderTotal),
        ];

        return apply_filters('ecommerce_cart_data_for_api_response', $cartData, [$products, $promotionDiscountAmount, $couponDiscountAmount, $couponCode]);
    }
}
