<?php

namespace Botble\Marketplace\Services;

use Botble\Location\Models\Country;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\StoreShippingCountry;
use Illuminate\Support\Collection;

class VendorShippingService
{
    /**
     * Update vendor shipping countries
     */
    public function updateShippingCountries(Store $store, array $countryIds): void
    {
        // Remove existing
        $store->shippingCountries()->detach();

        // Add new
        foreach ($countryIds as $countryId) {
            StoreShippingCountry::query()->create([
                'store_id' => $store->id,
                'country_id' => $countryId,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Get vendor shipping countries
     */
    public function getShippingCountries(Store $store): Collection
    {
        return $store->activeShippingCountries;
    }

    /**
     * Check if vendor ships to country
     */
    public function canShipTo(Store $store, int $countryId): bool
    {
        return $store->canShipToCountry($countryId);
    }

    /**
     * Enable shipping country
     */
    public function enableCountry(Store $store, int $countryId): void
    {
        StoreShippingCountry::query()
            ->where('store_id', $store->id)
            ->where('country_id', $countryId)
            ->update(['is_active' => true]);
    }

    /**
     * Disable shipping country
     */
    public function disableCountry(Store $store, int $countryId): void
    {
        StoreShippingCountry::query()
            ->where('store_id', $store->id)
            ->where('country_id', $countryId)
            ->update(['is_active' => false]);
    }

    /**
     * Get all available countries
     */
    public function getAllAvailableCountries(): Collection
    {
        return Country::query()
            ->where('status', 'published')
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get stores shipping to country
     */
    public function getStoresShippingToCountry(int $countryId): Collection
    {
        return Store::query()
            ->whereHas('activeShippingCountries', function ($q) use ($countryId) {
                $q->where('countries.id', $countryId);
            })
            ->get();
    }

    /**
     * Bulk assign countries to stores
     */
    public function bulkAssignCountries(array $storeIds, array $countryIds): void
    {
        foreach ($storeIds as $storeId) {
            $store = Store::query()->find($storeId);
            if ($store) {
                $this->updateShippingCountries($store, $countryIds);
            }
        }
    }
}
