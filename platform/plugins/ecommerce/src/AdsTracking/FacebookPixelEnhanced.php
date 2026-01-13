<?php

namespace Botble\Ecommerce\AdsTracking;

use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Illuminate\Support\Facades\Log;

class FacebookPixelEnhanced
{
    protected array $events = [];
    protected bool $debugMode = false;
    protected ?string $pixelId = null;

    public function __construct()
    {
        $this->debugMode = (bool) get_ecommerce_setting('facebook_pixel_debug_mode', false);
        $this->pixelId = get_ecommerce_setting('facebook_pixel_id');
    }

    public function isEnabled(): bool
    {
        return get_ecommerce_setting('facebook_pixel_enabled') && $this->pixelId;
    }

    public function viewContent(Product $product): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $this->pushEvent('ViewContent', [
            'content_ids' => [$product->id],
            'content_name' => $product->name,
            'content_type' => 'product',
            'content_category' => $product->categories->first()?->name,
            'value' => $product->price,
            'currency' => get_application_currency()->title,
        ]);

        return $this;
    }

    public function viewCategory(ProductCategory $category, array $products = []): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $productIds = collect($products)->pluck('id')->toArray();

        $this->pushEvent('ViewCategory', [
            'content_category' => $category->name,
            'content_ids' => $productIds,
            'content_type' => 'product_group',
        ]);

        return $this;
    }

    public function search(string $searchQuery, array $products = []): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $productIds = collect($products)->pluck('id')->toArray();

        $this->pushEvent('Search', [
            'search_string' => $searchQuery,
            'content_ids' => $productIds,
            'content_type' => 'product',
            'contents' => collect($products)->map(function ($product) {
                return ['id' => $product->id, 'quantity' => 1];
            })->toArray(),
        ]);

        return $this;
    }

    public function addToCart(Product $product, int $quantity = 1, ?float $value = null): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $this->pushEvent('AddToCart', [
            'content_ids' => [$product->id],
            'content_name' => $product->name,
            'content_type' => 'product',
            'contents' => [['id' => $product->id, 'quantity' => $quantity]],
            'value' => $value ?: ($product->price * $quantity),
            'currency' => get_application_currency()->title,
        ]);

        return $this;
    }

    public function addToWishlist(Product $product): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $this->pushEvent('AddToWishlist', [
            'content_ids' => [$product->id],
            'content_name' => $product->name,
            'content_type' => 'product',
            'value' => $product->price,
            'currency' => get_application_currency()->title,
        ]);

        return $this;
    }

    public function initiateCheckout(array $items, float $value, ?int $numItems = null): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $contents = collect($items)->map(function ($item) {
            return ['id' => $item['id'] ?? $item->id, 'quantity' => $item['quantity'] ?? $item->quantity ?? 1];
        })->toArray();

        $this->pushEvent('InitiateCheckout', [
            'content_ids' => collect($items)->pluck('id')->toArray(),
            'contents' => $contents,
            'content_type' => 'product',
            'value' => $value,
            'currency' => get_application_currency()->title,
            'num_items' => $numItems ?: count($items),
        ]);

        return $this;
    }

    public function addPaymentInfo(float $value, ?string $paymentMethod = null): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $data = [
            'value' => $value,
            'currency' => get_application_currency()->title,
        ];

        if ($paymentMethod) {
            $data['payment_type'] = $paymentMethod;
        }

        $this->pushEvent('AddPaymentInfo', $data);

        return $this;
    }

    public function purchase(Order $order): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $contents = $order->products->map(function ($product) {
            return ['id' => $product->product_id, 'quantity' => $product->qty];
        })->toArray();

        $this->pushEvent('Purchase', [
            'content_ids' => $order->products->pluck('product_id')->toArray(),
            'contents' => $contents,
            'content_type' => 'product',
            'value' => $order->amount,
            'currency' => get_application_currency()->title,
            'num_items' => $order->products->count(),
            'order_id' => $order->code,
        ]);

        return $this;
    }

    public function completeRegistration(?string $registrationMethod = null): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $data = [
            'status' => 'completed',
            'value' => 0,
            'currency' => get_application_currency()->title,
        ];

        if ($registrationMethod) {
            $data['registration_method'] = $registrationMethod;
        }

        $this->pushEvent('CompleteRegistration', $data);

        return $this;
    }

    public function lead(?float $value = null): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $data = [];

        if ($value) {
            $data['value'] = $value;
            $data['currency'] = get_application_currency()->title;
        }

        $this->pushEvent('Lead', $data);

        return $this;
    }

    public function customEvent(string $eventName, array $parameters = []): self
    {
        if (! $this->isEnabled()) {
            return $this;
        }

        $this->pushEvent($eventName, $parameters);

        return $this;
    }

    protected function pushEvent(string $eventName, array $parameters = []): void
    {
        try {
            if ($this->debugMode) {
                Log::info("Facebook Pixel Event: {$eventName}", $parameters);
            }

            $this->events[] = [
                'event' => $eventName,
                'parameters' => $parameters,
            ];
        } catch (\Throwable $e) {
            if ($this->debugMode) {
                Log::error("Facebook Pixel Error: {$e->getMessage()}");
            }
        }
    }

    public function render(): string
    {
        if (empty($this->events)) {
            return '';
        }

        $script = '';

        foreach ($this->events as $event) {
            $params = json_encode($event['parameters']);
            $script .= "fbq('track', '{$event['event']}', {$params});";

            if ($this->debugMode) {
                $script .= "console.log('FB Pixel Event: {$event['event']}', {$params});";
            }
        }

        return "<script>{$script}</script>";
    }

    public function renderBaseScript(): string
    {
        if (! $this->isEnabled()) {
            return '';
        }

        $debugScript = $this->debugMode ? $this->getDebugScript() : '';

        return <<<HTML
        {$debugScript}
        <!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{$this->pixelId}');
        fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" 
                src="https://www.facebook.com/tr?id={$this->pixelId}&ev=PageView&noscript=1"/>
        </noscript>
        <!-- End Meta Pixel Code -->
        HTML;
    }

    protected function getDebugScript(): string
    {
        return <<<HTML
        <script>
            window.fbPixelDebugMode = true;
            console.log('%c Facebook Pixel Debug Mode Enabled ', 'background: #1877F2; color: white; padding: 2px 5px; border-radius: 3px;');
            
            (function() {
                var originalFbq = window.fbq;
                window.fbq = function() {
                    if (arguments[0] === 'track' || arguments[0] === 'trackCustom') {
                        console.log('%c FB Pixel Event ', 'background: #42b883; color: white; padding: 2px 5px; border-radius: 3px;', arguments[1], arguments[2] || {});
                    }
                    if (originalFbq) {
                        return originalFbq.apply(this, arguments);
                    }
                };
                
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        if (window.fbq && window.fbq.loaded) {
                            console.log('%c ✓ Facebook Pixel Loaded Successfully ', 'background: #4CAF50; color: white; padding: 2px 5px; border-radius: 3px;');
                            console.log('Pixel ID: {$this->pixelId}');
                        } else {
                            console.warn('%c ⚠ Facebook Pixel may not be loaded properly ', 'background: #FF9800; color: white; padding: 2px 5px; border-radius: 3px;');
                        }
                    }, 2000);
                });
            })();
        </script>
        HTML;
    }

    public function withAdvancedMatching(array $userData = []): self
    {
        if (empty($userData) || ! $this->isEnabled()) {
            return $this;
        }

        $allowedFields = ['em', 'fn', 'ln', 'ph', 'ge', 'db', 'ct', 'st', 'zp', 'country'];
        $matchingData = [];

        foreach ($userData as $key => $value) {
            if (in_array($key, $allowedFields) && $value) {
                $matchingData[$key] = hash('sha256', strtolower(trim($value)));
            }
        }

        if (! empty($matchingData)) {
            $this->events[] = [
                'event' => 'init',
                'parameters' => [
                    'pixel_id' => $this->pixelId,
                    'advanced_matching' => $matchingData,
                ],
            ];
        }

        return $this;
    }
}
