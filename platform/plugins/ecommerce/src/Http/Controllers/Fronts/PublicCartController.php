<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\AdsTracking\FacebookPixel;
use Botble\Ecommerce\AdsTracking\GoogleTagManager;
use Botble\Ecommerce\Cart\Cart as CartInstance;
use Botble\Ecommerce\Enums\DiscountTypeEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Http\Requests\CartRequest;
use Botble\Ecommerce\Http\Requests\UpdateCartRequest;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleApplyPromotionsService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Throwable;

class PublicCartController extends BaseController
{
    protected ?array $cachedCartData = null;

    protected ?object $cachedCartInstance = null;

    public function __construct(
        protected HandleApplyPromotionsService $applyPromotionsService,
        protected HandleApplyCouponService $handleApplyCouponService
    ) {
    }

    protected function getCartInstance(): CartInstance
    {
        if ($this->cachedCartInstance === null) {
            $this->cachedCartInstance = Cart::instance('cart');
        }

        return $this->cachedCartInstance;
    }

    public function index()
    {
        $promotionDiscountAmount = 0;
        $couponDiscountAmount = 0;

        $products = new Collection();
        $crossSellProducts = new Collection();

        if (Cart::instance('cart')->isNotEmpty()) {
            [$products, $promotionDiscountAmount, $couponDiscountAmount] = $this->getCartData();

            $crossSellProducts = get_cart_cross_sale_products(
                $products->pluck('original_product.id')->all(),
                (int) theme_option('number_of_cross_sale_product', 4)
            ) ?: new Collection();
        }

        $title = __('Shopping Cart');

        SeoHelper::setTitle(theme_option('ecommerce_cart_seo_title') ?: $title)
            ->setDescription(theme_option('ecommerce_cart_seo_description'));

        Theme::breadcrumb()->add($title, route('public.cart'));

        app(GoogleTagManager::class)->viewCart();

        return Theme::scope(
            'ecommerce.cart',
            compact('promotionDiscountAmount', 'couponDiscountAmount', 'products', 'crossSellProducts'),
            'plugins/ecommerce::themes.cart'
        )->render();
    }

    public function store(CartRequest $request)
    {
        $response = $this->httpResponse();

        /**
         * @var Product $product
         */
        $product = Product::query()
            ->find($request->input('id'));

        if (! $product) {
            return $response
                ->setError()
                ->setMessage(__('This product is out of stock or not exists!'));
        }

        if ($product->variations->isNotEmpty() && ! $product->is_variation && $product->defaultVariation->product->id) {
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
                );
        }

        try {
            do_action('ecommerce_before_add_to_cart', $product);
        } catch (Exception $e) {
            return $response
                ->setError()
                ->setMessage($e->getMessage());
        }

        $maxQuantity = $product->max_cart_quantity;

        $requestQuantity = $request->integer('qty', 1);

        $existingAddedToCart = Cart::instance('cart')->content()->firstWhere('id', $product->id);

        if ($existingAddedToCart) {
            $requestQuantity += $existingAddedToCart->qty;
        }

        if (! $product->canAddToCart($requestQuantity)) {
            return $response
                ->setError()
                ->setMessage(__('Sorry, you can only order a maximum of :quantity units of :product at a time. Please adjust the quantity and try again.', ['quantity' => $maxQuantity, 'product' => $product->name]));
        }

        $outOfQuantity = false;
        $cartContent = Cart::instance('cart')->content();
        $existingItem = $cartContent->firstWhere('id', $product->id);

        if ($existingItem) {
            $originalQuantity = $product->quantity;
            $product->quantity = (int) $product->quantity - $existingItem->qty;

            if ($product->quantity < 0) {
                $product->quantity = 0;
            }

            if ($product->isOutOfStock()) {
                $outOfQuantity = true;
            }

            $product->quantity = $originalQuantity;
        }

        $product->quantity = (int) $product->quantity - $request->integer('qty', 1);

        if (
            EcommerceHelper::isEnabledProductOptions() &&
            DB::table('ec_options')
                ->where('product_id', $originalProduct->id)
                ->where('required', true)
                ->exists()
        ) {
            if (! $request->input('options')) {
                return $response
                    ->setError()
                    ->setData(['next_url' => $originalProduct->url])
                    ->setMessage(__('Please select product options!'));
            }

            $requiredOptions = DB::table('ec_options')
                ->where('product_id', $originalProduct->id)
                ->where('required', true)
                ->get();

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
                return $response
                    ->setError()
                    ->setMessage(__('Please select product options!'));
            }
        }

        if ($outOfQuantity) {
            return $response
                ->setError()
                ->setMessage(__(
                    'Product :product is out of stock!',
                    ['product' => $originalProduct->name ?: $product->name]
                ));
        }

        try {
            $cartItems = OrderHelper::handleAddCart($product, $request);
        } catch (Exception $e) {
            return $response
                ->setError()
                ->setMessage($e->getMessage());
        }

        $cartItem = Arr::first(array_filter($cartItems, fn ($item) => $item['id'] == $product->id));

        $response->setMessage(__(
            'Added product :product to cart successfully!',
            ['product' => $originalProduct->name ?: $product->name]
        ));

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

        $token = OrderHelper::getOrderSessionToken();
        $nextUrl = route('public.checkout.information', $token);

        if (EcommerceHelper::getQuickBuyButtonTarget() == 'cart') {
            $nextUrl = route('public.cart');
        }

        if ($request->input('checkout')) {
            Cart::instance('cart')->refresh();

            $responseData['next_url'] = $nextUrl;

            $this->applyAutoCouponCode();

            if ($request->ajax() && $request->wantsJson()) {
                return $response->setData($responseData);
            }

            return $response
                ->setData($responseData)
                ->setNextUrl($nextUrl);
        }

        return $response
            ->setData([
                ...$this->getDataForResponse(),
                ...$responseData,
            ]);
    }

    public function update(UpdateCartRequest $request)
    {
        if ($request->has('checkout')) {
            $token = OrderHelper::getOrderSessionToken();

            return $this
                ->httpResponse()
                ->setNextUrl(route('public.checkout.information', $token));
        }

        $data = $request->input('items', []);

        $outOfQuantity = false;
        foreach ($data as $item) {
            $cartItem = Cart::instance('cart')->get($item['rowId']);

            if (! $cartItem) {
                continue;
            }

            /**
             * @var Product $product
             */
            $product = Product::query()->find($cartItem->id);

            if ($product) {
                $originalQuantity = $product->quantity;
                $product->quantity = (int) $product->quantity - (int) Arr::get($item, 'values.qty', 0) + 1;

                if ($product->quantity < 0) {
                    $product->quantity = 0;
                }

                if ($product->isOutOfStock()) {
                    $outOfQuantity = true;
                } else {
                    Cart::instance('cart')->update($item['rowId'], Arr::get($item, 'values'));
                }

                $product->quantity = $originalQuantity;
            }
        }

        if ($outOfQuantity) {
            return $this
                ->httpResponse()
                ->setError()
                ->setData($this->getDataForResponse())
                ->setMessage(__('One or all products are not enough quantity so cannot update!'));
        }

        return $this
            ->httpResponse()
            ->setData($this->getDataForResponse())
            ->setMessage(__('Update cart successfully!'));
    }

    public function destroy(string $id)
    {
        try {
            $cartItem = Cart::instance('cart')->get($id);
            app(GoogleTagManager::class)->removeFromCart($cartItem);

            Cart::instance('cart')->remove($id);

            $responseData = [
                ...$this->getDataForResponse(),
            ];

            return $this
                ->httpResponse()
                ->setData($responseData)
                ->setMessage(__('Removed item from cart successfully!'));
        } catch (Throwable) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Cart item is not existed!'));
        }
    }

    public function empty()
    {
        Cart::instance('cart')->destroy();

        return $this
            ->httpResponse()
            ->setData(Cart::instance('cart')->content())
            ->setMessage(__('Empty cart successfully!'));
    }

    protected function getCartData(): array
    {
        if ($this->cachedCartData !== null) {
            return $this->cachedCartData;
        }

        $cartInstance = $this->getCartInstance();
        $products = $cartInstance->products();

        $cartData = [
            'rawTotal' => $cartInstance->rawTotal(),
            'cartItems' => $cartInstance->content(),
            'countCart' => $cartInstance->count(),
            'productItems' => $products,
        ];

        $promotionDiscountAmount = $this->applyPromotionsService->execute(null, $cartData);

        $couponDiscountAmount = $this->applyAutoCouponCode();

        $sessionData = OrderHelper::getOrderSessionData();

        if (session()->has('applied_coupon_code')) {
            $couponDiscountAmount = (float) Arr::get($sessionData, 'coupon_discount_amount', 0);
        }

        $this->cachedCartData = [$products, $promotionDiscountAmount, $couponDiscountAmount];

        return $this->cachedCartData;
    }

    protected function getDataForResponse(): array
    {
        $cartContent = null;

        $cartInstance = $this->getCartInstance();

        $cartData = $this->getCartData();

        [$products, $promotionDiscountAmount, $couponDiscountAmount] = $cartData;

        $cartCount = $cartInstance->count();
        $cartSubTotal = $cartInstance->rawSubTotal();
        $cartContentData = $cartInstance->content();

        if (Route::is('public.cart.*')) {
            $crossSellProducts = collect();
            if ($products->isNotEmpty()) {
                $productIds = $products->pluck('original_product.id')->filter()->unique()->all();

                if (! empty($productIds)) {
                    $crossSellProducts = get_cart_cross_sale_products(
                        $productIds,
                        (int) theme_option('number_of_cross_sale_product', 4)
                    ) ?: collect();
                }
            }

            $cartContent = view(
                EcommerceHelper::viewPath('cart'),
                compact('products', 'promotionDiscountAmount', 'couponDiscountAmount', 'crossSellProducts')
            )->render();
        }

        $additionalData = apply_filters('ecommerce_cart_additional_data', [], $cartData);

        return apply_filters('ecommerce_cart_data_for_response', [
            'count' => $cartCount,
            'total_price' => format_price($cartSubTotal),
            'content' => $cartContentData,
            'cart_content' => $cartContent,
            ...$additionalData,
        ], $cartData);
    }

    protected function applyAutoCouponCode(): float
    {
        $couponDiscountAmount = 0;

        if ($couponCode = session('auto_apply_coupon_code')) {
            $coupon = Discount::query()
                ->where('code', $couponCode)
                ->where('apply_via_url', true)
                ->where('type', DiscountTypeEnum::COUPON)
                ->exists();

            if ($coupon) {
                $couponData = $this->handleApplyCouponService->execute($couponCode);

                if (! Arr::get($couponData, 'error')) {
                    $couponDiscountAmount = Arr::get($couponData, 'data.discount_amount', 0);
                }
            }
        }

        return (float) $couponDiscountAmount;
    }
}
