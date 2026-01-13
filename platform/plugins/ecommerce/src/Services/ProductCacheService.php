<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Language\Facades\Language;
use Botble\Support\Services\Cache\Cache;

class ProductCacheService
{
    protected Cache $cache;

    public function __construct()
    {
        $this->cache = Cache::make('ecommerce_products');
    }

    public function clearProductCache(Product $product): void
    {
        $locales = is_plugin_active('language') ? array_keys(Language::getSupportedLocales()) : [app()->getLocale()];

        foreach ($locales as $locale) {
            for ($limit = 1; $limit <= 20; $limit++) {
                $this->forgetKey('related_products_' . $product->getKey() . '_' . $limit . '_' . $locale);
                $this->forgetKey('cross_sale_products_' . $product->getKey() . '_' . $limit . '_' . md5(json_encode([])) . '_' . $locale);
                $this->forgetKey('cross_sale_products_' . $product->getKey() . '_' . $limit . '_' . md5(json_encode(EcommerceHelper::withProductEagerLoadingRelations())) . '_' . $locale);

                foreach ([1, 3, 7, 14, 30, 60, 90] as $days) {
                    $this->forgetKey('trending_products_' . $limit . '_' . $days . '_' . $locale);
                }
            }
        }

        $relatedProductIds = $product->products()->allRelatedIds()->toArray();
        foreach ($relatedProductIds as $relatedId) {
            foreach ($locales as $locale) {
                for ($limit = 1; $limit <= 20; $limit++) {
                    $this->forgetKey('related_products_' . $relatedId . '_' . $limit . '_' . $locale);
                }
            }
        }

        if ($product->categories) {
            $categoryIds = $product->categories->pluck('id')->toArray();
            Product::query()
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('ec_product_categories.id', $categoryIds);
                })
                ->where('id', '!=', $product->getKey())
                ->limit(100)
                ->pluck('id')
                ->each(function ($categoryProductId) use ($locales) {
                    foreach ($locales as $locale) {
                        for ($limit = 1; $limit <= 20; $limit++) {
                            $this->forgetKey('related_products_' . $categoryProductId . '_' . $limit . '_' . $locale);
                        }
                    }
                });
        }

        if ($product->brand_id) {
            Product::query()
                ->where('brand_id', $product->brand_id)
                ->where('id', '!=', $product->getKey())
                ->limit(100)
                ->pluck('id')
                ->each(function ($brandProductId) use ($locales) {
                    foreach ($locales as $locale) {
                        for ($limit = 1; $limit <= 20; $limit++) {
                            $this->forgetKey('related_products_' . $brandProductId . '_' . $limit . '_' . $locale);
                        }
                    }
                });
        }

        $crossSaleProductIds = $product->crossSales()->pluck('ec_products.id')->toArray();
        foreach ($crossSaleProductIds as $crossSaleId) {
            foreach ($locales as $locale) {
                for ($limit = 1; $limit <= 20; $limit++) {
                    $this->forgetKey('cross_sale_products_' . $crossSaleId . '_' . $limit . '_' . md5(json_encode([])) . '_' . $locale);
                    $this->forgetKey('cross_sale_products_' . $crossSaleId . '_' . $limit . '_' . md5(json_encode(EcommerceHelper::withProductEagerLoadingRelations())) . '_' . $locale);
                }
            }
        }

        Product::query()
            ->whereHas('crossSales', function ($query) use ($product) {
                $query->where('ec_products.id', $product->getKey());
            })
            ->limit(100)
            ->pluck('id')
            ->each(function ($relatedProductId) use ($locales) {
                foreach ($locales as $locale) {
                    for ($limit = 1; $limit <= 20; $limit++) {
                        $this->forgetKey('cross_sale_products_' . $relatedProductId . '_' . $limit . '_' . md5(json_encode([])) . '_' . $locale);
                        $this->forgetKey('cross_sale_products_' . $relatedProductId . '_' . $limit . '_' . md5(json_encode(EcommerceHelper::withProductEagerLoadingRelations())) . '_' . $locale);
                    }
                }
            });
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        return $this->cache->remember($key, $ttl, $callback);
    }

    protected function forgetKey(string $key): bool
    {
        return $this->cache->forget($this->cache->generateCacheKey($key));
    }

    public function forget(string $key): bool
    {
        return $this->cache->forget($this->cache->generateCacheKey($key));
    }

    public function flush(): bool
    {
        return $this->cache->flush();
    }
}
