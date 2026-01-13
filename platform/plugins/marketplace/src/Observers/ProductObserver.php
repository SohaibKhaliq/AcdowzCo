<?php

namespace Botble\Marketplace\Observers;

use Botble\Ecommerce\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    /**
     * Handle the Product "saved" event.
     */
    public function saved(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Clear vendor categories cache
     */
    protected function clearCache(Product $product): void
    {
        // Only clear cache if the product has a store_id
        if ($product->store_id) {
            $cacheKey = 'marketplace_store_categories_' . $product->store_id;
            Cache::forget($cacheKey);
        }

        // If store_id changed, clear cache for the old store as well
        if ($product->wasChanged('store_id') && $product->getOriginal('store_id')) {
            $oldCacheKey = 'marketplace_store_categories_' . $product->getOriginal('store_id');
            Cache::forget($oldCacheKey);
        }
    }
}
