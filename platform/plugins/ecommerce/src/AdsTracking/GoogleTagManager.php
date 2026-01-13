<?php

namespace Botble\Ecommerce\AdsTracking;

use Botble\Ecommerce\Cart\CartItem;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class GoogleTagManager
{
    protected array $dataLayer = [];

    public function isEnabled(): bool
    {
        $enabled = get_ecommerce_setting('google_tag_manager_enabled', false);

        if (is_string($enabled)) {
            $enabled = $enabled === '1' || $enabled === 'true';
        }

        if (! $enabled) {
            return false;
        }

        $type = setting('google_tag_manager_type');

        if (! $type) {
            if (setting('gtm_container_id')) {
                $type = 'gtm';
            } elseif (setting('google_tag_manager_code')) {
                $type = 'code';
            } elseif (setting('custom_tracking_header_js') || setting('custom_tracking_body_html')) {
                $type = 'custom';
            } elseif (setting('google_tag_manager_id') || setting('google_analytics')) {
                $type = 'id';
            } else {
                return false;
            }
        }

        return match ($type) {
            'gtm' => (bool) setting('gtm_container_id'),
            'id' => (setting('google_tag_manager_id') || setting('google_analytics')),
            'custom' => (
                setting('custom_tracking_header_js') ||
                setting('custom_tracking_body_html') ||
                setting('google_tag_manager_code')
            ),
            'code' => (
                setting('google_tag_manager_code') ||
                setting('custom_tracking_header_js') ||
                setting('custom_tracking_body_html')
            ),
            default => (
                setting('gtm_container_id') ||
                setting('google_tag_manager_id') ||
                setting('google_analytics') ||
                setting('google_tag_manager_code') ||
                setting('custom_tracking_header_js') ||
                setting('custom_tracking_body_html')
            ),
        };
    }

    public function viewItemList(array $items, string $name, array $attributes = []): self
    {
        $this->pushEvent('view_item_list', $items, [
            'item_list_id' => Str::snake($name),
            'item_list_name' => $name,
            ...$attributes,
        ]);

        return $this;
    }

    public function viewItem(Product $item, array $attributes = []): self
    {
        $this->pushEvent('view_item', [$item], [
            'currency' => get_application_currency()->title,
            'value' => $item->price,
            ...$attributes,
        ]);

        return $this;
    }

    public function selectItem(Product $item, string $listName = '', int $index = 0, array $attributes = []): self
    {
        $this->pushEvent('select_item', [$item], [
            'item_list_id' => Str::snake($listName),
            'item_list_name' => $listName,
            'index' => $index,
            ...$attributes,
        ]);

        return $this;
    }

    public function search(string $searchTerm, array $items = [], array $attributes = []): self
    {
        $this->pushEvent('search', $items, [
            'search_term' => $searchTerm,
            ...$attributes,
        ]);

        return $this;
    }

    public function viewPromotion(array $items, string $promotionId = '', string $promotionName = '', array $attributes = []): self
    {
        $this->pushEvent('view_promotion', $items, [
            'promotion_id' => $promotionId,
            'promotion_name' => $promotionName,
            ...$attributes,
        ]);

        return $this;
    }

    public function selectPromotion(array $items, string $promotionId = '', string $promotionName = '', array $attributes = []): self
    {
        $this->pushEvent('select_promotion', $items, [
            'promotion_id' => $promotionId,
            'promotion_name' => $promotionName,
            ...$attributes,
        ]);

        return $this;
    }

    public function addToCart(Product $item, int $quantity, float $value, array $attributes = []): self
    {
        $item->quantity = $quantity;

        $this->pushEvent('add_to_cart', [$item], [
            'currency' => get_application_currency()->title,
            'value' => $value,
            ...$attributes,
        ]);

        return $this;
    }

    public function viewCart(array $attributes = []): self
    {
        $cart = Cart::instance('cart');
        $products = $cart->products();

        $items = $cart->content()->map(function ($item) use ($products) {
            /**
             * @var Collection $products
             */
            $product = $products->find($item->id)->original_product;

            return new GoogleTagItem(
                $item->sku ?: $item->id,
                $item->name,
                $item->price,
                $item->qty,
                [
                    ...$this->formatItemAttributes($product),
                    'item_variant' => $item->options->attributes,
                ]
            );
        })->values()->all();

        $this->pushEvent('view_cart', $items, [
            'currency' => get_application_currency()->title,
            'value' => $cart->rawSubTotal(),
            ...$attributes,
        ]);

        return $this;
    }

    public function removeFromCart(CartItem $cartItem, array $attributes = []): self
    {
        $product = Product::query()->find($cartItem->id)->original_product;
        $product->quantity = $cartItem->qty;

        $this->pushEvent('remove_from_cart', [$product->original_product], [
            'currency' => get_application_currency()->title,
            'value' => $cartItem->price * $cartItem->qty,
            ...$attributes,
        ]);

        return $this;
    }

    public function beginCheckout(array $items, float $value, ?string $coupon = null, array $attributes = []): self
    {
        $this->pushEvent('begin_checkout', $items, [
            'currency' => get_application_currency()->title,
            'value' => $value,
            'coupon' => $coupon,
            ...$attributes,
        ]);

        return $this;
    }

    public function purchase(Order $order, array $attributes = [], array $products = []): self
    {
        $products = $products ?: $order->getOrderProducts()->all();

        $this->pushEvent('purchase', $products, [
            'transaction_id' => $order->code,
            'currency' => get_application_currency()->title,
            'value' => $order->sub_total,
            'tax' => $order->tax_amount,
            'shipping' => $order->shipping_amount,
            'coupon' => $order->coupon_code,
            ...$attributes,
        ]);

        return $this;
    }

    public function refund(Order $order, array $attributes = []): self
    {
        $products = $order->getOrderProducts();

        $this->pushEvent('refund', $products->all(), [
            'transaction_id' => $order->code,
            'currency' => get_application_currency()->title,
            'value' => $order->sub_total,
            'tax' => $order->tax_amount,
            'shipping' => $order->shipping_amount,
            'coupon' => $order->coupon_code,
            ...$attributes,
        ]);

        return $this;
    }

    public function pushEvent(string $event, array|\Illuminate\Support\Collection $items, array $attributes = []): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        if ($items instanceof Collection) {
            $firstItem = $items->first();
            if ($firstItem instanceof Product) {
                $items->loadMissing(['brand', 'categories']);
            }
            $items = $items->all();
        }

        $items = array_map(fn (GoogleTagItem $item) => $item->toArray(), $this->formatItems($items));

        $data = apply_filters('ecommerce.google_tag_manager.push_event', [
            ...$attributes,
            'items' => $items,
        ], $event, $items, $attributes);

        $this->dataLayer[$event] = $data;

        return $this;
    }

    public function render(): string
    {
        if (empty($this->dataLayer)) {
            return '';
        }

        $gtag = '';

        foreach ($this->dataLayer as $event => $data) {
            $gtag .= "gtag('event', '$event', " . json_encode($data) . ');';
        }

        return <<<HTML
            <script>
                if (typeof gtag !== "undefined") {
                    $gtag
                }
            </script>
        HTML;
    }

    public function pushScriptsToFooter(): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        add_filter(THEME_FRONT_FOOTER, function (?string $html) {
            return $html . view(EcommerceHelper::viewPath('includes.gtm-script'))->render() . $this->render();
        }, 999);

        add_filter('ecommerce_checkout_footer', function (?string $html) {
            return $html . SeoHelper::meta()->getAnalytics()->render() . $this->render();
        }, 999);
    }

    public function formatItems(array|\Illuminate\Support\Collection $items): array
    {
        if ($items instanceof \Illuminate\Support\Collection) {
            $items = $items->all();
        }

        $productsToLoad = array_filter($items, fn ($item) => ! ($item instanceof GoogleTagItem));

        if (! empty($productsToLoad)) {
            $brandIds = array_unique(array_filter(array_map(fn ($item) => $item->brand_id, $productsToLoad)));

            if (! empty($brandIds)) {
                $brands = Brand::query()
                    ->whereIn('id', $brandIds)
                    ->get()
                    ->keyBy('id');

                foreach ($productsToLoad as $product) {
                    if ($product->brand_id && $brands->has($product->brand_id)) {
                        $product->setRelation('brand', $brands->get($product->brand_id));
                    }
                }
            }

            $productsNeedingCategories = array_filter($productsToLoad, fn ($item) => ! $item->relationLoaded('categories'));
            if (! empty($productsNeedingCategories)) {
                $productIds = array_map(fn ($item) => $item->id, $productsNeedingCategories);
                $categories = ProductCategory::query()
                    ->join('ec_product_category_product', 'ec_product_categories.id', '=', 'ec_product_category_product.category_id')
                    ->whereIn('ec_product_category_product.product_id', $productIds)
                    ->select('ec_product_categories.*', 'ec_product_category_product.product_id')
                    ->get()
                    ->groupBy('product_id');

                foreach ($productsNeedingCategories as $product) {
                    if ($categories->has($product->id)) {
                        $product->setRelation('categories', $categories->get($product->id));
                    }
                }
            }
        }

        return array_map(function ($item) {
            if ($item instanceof GoogleTagItem) {
                return $item;
            }

            return new GoogleTagItem(
                id: $item->sku ?: $item->id,
                name: $item->name,
                price: $item->price ?: 0,
                quantity: $item->quantity ?? null,
                attributes: $this->formatItemAttributes($item),
            );
        }, $items);
    }

    public function formatItemAttributes(Product $product): array
    {
        $attributes = [];

        if ($product->brand) {
            $attributes['item_brand'] = $product->brand->name;
        }

        if ($product->categories) {
            foreach ($product->categories as $key => $category) {
                $keyName = $key === 0 ? '' : $key + 1;
                $attributes["item_category$keyName"] = $category->name;
            }
        }

        return $attributes;
    }
}
