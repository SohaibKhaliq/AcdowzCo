<?php

namespace Botble\Ecommerce\Services\Location;

use Botble\Ecommerce\Models\Product;
use Botble\Marketplace\Models\Store;
use Illuminate\Database\Eloquent\Builder;

class ProductAvailabilityService
{
    public function __construct(
        protected CountryDetectionService $countryDetectionService
    ) {}

    /**
     * Apply country filter to product query
     */
    public function applyCountryFilter(Builder $query, ?int $countryId = null): Builder
    {
        $countryId = $countryId ?? $this->countryDetectionService->getCurrentCountryId();

        if (!$countryId) {
            return $query;
        }

        return $query->whereHas('countries', function ($q) use ($countryId) {
            $q->where('countries.id', $countryId)
              ->where('countries.status', 'published');
        })->whereHas('store', function ($q) use ($countryId) {
            $q->whereHas('activeShippingCountries', function ($sq) use ($countryId) {
                $sq->where('countries.id', $countryId);
            });
        });
    }

    /**
     * Check if product is available in country
     */
    public function isProductAvailable(Product $product, ?int $countryId = null): bool
    {
        $countryId = $countryId ?? $this->countryDetectionService->getCurrentCountryId();

        if (!$countryId) {
            return true;
        }

        // Check product countries
        $productAvailable = $product->countries()
            ->where('countries.id', $countryId)
            ->where('countries.status', 'published')
            ->exists();

        if (!$productAvailable) {
            return false;
        }

        // Check store shipping countries
        if ($product->store_id) {
            $store = $product->store;
            return $store && $store->canShipToCountry($countryId);
        }

        return true;
    }

    /**
     * Get available countries for product
     */
    public function getProductAvailableCountries(Product $product): array
    {
        $productCountries = $product->countries()
            ->where('countries.status', 'published')
            ->pluck('countries.id')
            ->toArray();

        if ($product->store_id && $product->store) {
            $storeCountries = $product->store->activeShippingCountries()
                ->pluck('countries.id')
                ->toArray();

            // Intersection - product must be available AND store must ship there
            return array_intersect($productCountries, $storeCountries);
        }

        return $productCountries;
    }

    /**
     * Filter products by availability
     */
    public function filterAvailableProducts($products, ?int $countryId = null)
    {
        $countryId = $countryId ?? $this->countryDetectionService->getCurrentCountryId();

        if (!$countryId) {
            return $products;
        }

        return $products->filter(function ($product) use ($countryId) {
            return $this->isProductAvailable($product, $countryId);
        });
    }

    /**
     * Get unavailable message
     */
    public function getUnavailableMessage(?int $countryId = null): string
    {
        $countryName = $this->countryDetectionService->getCountryName($countryId);
        
        if ($countryName) {
            return trans('plugins/ecommerce::product.not_available_in_country', ['country' => $countryName]);
        }

        return trans('plugins/ecommerce::product.not_available_in_your_region');
    }
}
