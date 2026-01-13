<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Models\Product;

class UpdateProductStockStatus
{
    public function handle(ProductQuantityUpdatedEvent $event): void
    {
        $product = $event->product;

        // If this is a variation, update the parent product
        if ($product->is_variation) {
            $this->updateParentProduct($product);
        }

        // If this is a parent product with variations, update it based on variations
        if (! $product->is_variation && $product->variations()->exists()) {
            $this->updateParentProductFromVariations($product);
        }
    }

    protected function updateParentProduct(Product $variationProduct): void
    {
        $parentProduct = $variationProduct->original_product;

        if (! $parentProduct || ! $parentProduct->id || $parentProduct->is_variation) {
            return;
        }

        $this->updateParentProductFromVariations($parentProduct);
    }

    protected function updateParentProductFromVariations(Product $parentProduct): void
    {
        $variations = $parentProduct->variations()->with('product')->get();

        if ($variations->isEmpty()) {
            return;
        }

        $quantity = 0;
        $withStorehouseManagement = false;
        $stockStatus = StockStatusEnum::OUT_OF_STOCK;
        $allowCheckoutWhenOutOfStock = false;

        foreach ($variations as $variation) {
            $variationProduct = $variation->product;

            if (! $variationProduct || ! $variationProduct->is_variation) {
                continue;
            }

            if ($variationProduct->with_storehouse_management) {
                $quantity += $variationProduct->quantity;
                $withStorehouseManagement = true;
            }

            if ($variationProduct->allow_checkout_when_out_of_stock) {
                $allowCheckoutWhenOutOfStock = true;
            }

            if (! $variationProduct->isOutOfStock()) {
                $stockStatus = $variationProduct->stock_status == StockStatusEnum::ON_BACKORDER
                    ? StockStatusEnum::ON_BACKORDER
                    : StockStatusEnum::IN_STOCK;
            }
        }

        // Only update if there are actual changes to prevent infinite loops
        $hasChanges = $parentProduct->quantity != $quantity ||
                     $parentProduct->with_storehouse_management != $withStorehouseManagement ||
                     $parentProduct->stock_status != $stockStatus ||
                     $parentProduct->allow_checkout_when_out_of_stock != $allowCheckoutWhenOutOfStock;

        if ($hasChanges) {
            $parentProduct->quantity = $quantity;
            $parentProduct->with_storehouse_management = $withStorehouseManagement;
            $parentProduct->stock_status = $stockStatus;
            $parentProduct->allow_checkout_when_out_of_stock = $allowCheckoutWhenOutOfStock;

            $parentProduct->saveQuietly(); // Use saveQuietly to prevent triggering events again
        }
    }
}
