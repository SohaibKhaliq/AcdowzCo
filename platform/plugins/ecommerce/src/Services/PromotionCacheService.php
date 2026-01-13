<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Facades\Cart;
use Botble\Support\Services\Cache\Cache;

class PromotionCacheService
{
    protected const CACHE_GROUP = 'ecommerce_promotions';

    protected const CACHE_TTL = 3600;

    protected static array $memoryCache = [];

    protected Cache $cache;

    public function __construct()
    {
        $this->cache = Cache::make(self::CACHE_GROUP);
    }

    public function getCacheKey(array $data = []): string
    {
        $cartInstance = Cart::instance('cart');

        $keyData = [
            'cart_items' => $cartInstance->content()->map(fn ($item) => [
                'id' => $item->id,
                'qty' => $item->qty,
                'price' => $item->price,
            ])->toArray(),
            'customer_id' => auth('customer')->id(),
            'raw_total' => $data['rawTotal'] ?? $cartInstance->rawTotal(),
            'count' => $data['countCart'] ?? $cartInstance->count(),
        ];

        return md5(serialize($keyData));
    }

    public function get(string $key)
    {
        if (isset(self::$memoryCache[$key])) {
            return self::$memoryCache[$key];
        }

        $value = $this->cache->get($key);

        if ($value !== null) {
            self::$memoryCache[$key] = $value;
        }

        return $value;
    }

    public function put(string $key, $value, ?int $ttl = null): void
    {
        $ttl = $ttl ?? self::CACHE_TTL;

        self::$memoryCache[$key] = $value;

        $this->cache->put($key, $value, $ttl);
    }

    public function remember(string $key, $callback, ?int $ttl = null)
    {
        if (isset(self::$memoryCache[$key])) {
            return self::$memoryCache[$key];
        }

        $ttl = $ttl ?? self::CACHE_TTL;
        $value = $this->cache->remember($key, $ttl, $callback);

        if ($value !== null) {
            self::$memoryCache[$key] = $value;
        }

        return $value;
    }

    public function forget(string $key): void
    {
        unset(self::$memoryCache[$key]);

        $this->cache->forget($key);
    }

    public function flush(): void
    {
        self::$memoryCache = [];

        $this->cache->flush();
    }
}
