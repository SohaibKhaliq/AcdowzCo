<?php

namespace Botble\Ecommerce\Observers;

use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Services\PromotionCacheService;

class DiscountObserver
{
    protected PromotionCacheService $cacheService;

    public function __construct()
    {
        $this->cacheService = new PromotionCacheService();
    }

    public function created(Discount $discount): void
    {
        $this->clearCache();
    }

    public function updated(Discount $discount): void
    {
        $this->clearCache();
    }

    public function deleted(Discount $discount): void
    {
        $this->clearCache();
    }

    public function restored(Discount $discount): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Discount $discount): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        $this->cacheService->flush();
    }
}
