<?php

namespace Botble\Marketplace\Listeners;

use Botble\Ecommerce\Models\Product;
use Illuminate\Support\Facades\Cache;

class ClearVendorCategoriesCacheOnSync
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        // Get the parent model from the event
        $parent = $event->parent ?? null;

        if (! $parent instanceof Product) {
            return;
        }

        // Only clear cache if the product has a store_id
        if ($parent->store_id) {
            $cacheKey = 'marketplace_store_categories_' . $parent->store_id;
            Cache::forget($cacheKey);
        }
    }
}
