<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderCompletedEvent;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductLicenseCode;
use Illuminate\Support\Str;

class GenerateLicenseCodeAfterOrderCompleted
{
    public function handle(OrderCompletedEvent $event): void
    {
        if (! EcommerceHelper::isEnabledLicenseCodesForDigitalProducts()) {
            return;
        }

        if (($order = $event->order) instanceof Order && $order->loadMissing(['products.product'])) {
            $orderProducts = $order->products
                ->where(function ($item) {
                    return $item->product->isTypeDigital() && $item->product->generate_license_code;
                });

            $invoiceItems = $order->invoice->items;
            foreach ($orderProducts as $orderProduct) {
                $licenseCodes = [];
                $quantity = $orderProduct->qty ?? 1;

                // Generate license codes based on quantity
                for ($i = 0; $i < $quantity; $i++) {
                    $licenseCode = null;

                    // Check the license code assignment method
                    if ($orderProduct->product->license_code_type === 'pick_from_list') {
                        // First, try to get an available license code from the specific product (variation or main product)
                        $availableLicenseCode = ProductLicenseCode::query()
                            ->forProduct($orderProduct->product_id)
                            ->available()
                            ->first();

                        // If no codes available and this is a variation, try the main product
                        if (! $availableLicenseCode && $orderProduct->product->is_variation) {
                            $mainProduct = $orderProduct->product->variationInfo?->configurableProduct;
                            if ($mainProduct && $mainProduct->license_code_type === 'pick_from_list') {
                                $availableLicenseCode = ProductLicenseCode::query()
                                    ->forProduct($mainProduct->id)
                                    ->available()
                                    ->first();
                            }
                        }

                        if ($availableLicenseCode) {
                            // Use existing license code from pool
                            $licenseCode = $availableLicenseCode->license_code;
                            $availableLicenseCode->markAsUsed($orderProduct);
                        } else {
                            // No codes available in any pool - log warning and fallback to auto-generate
                            $productInfo = $orderProduct->product->is_variation
                                ? "variation ID: {$orderProduct->product_id} (main product: {$orderProduct->product->variationInfo?->configurable_product_id})"
                                : "product ID: {$orderProduct->product_id}";
                            logger()->warning("No available license codes found for {$productInfo}. Falling back to auto-generation.");
                            $licenseCode = Str::uuid();
                        }
                    } else {
                        // Auto-generate mode - always generate a new UUID
                        $licenseCode = Str::uuid();
                    }

                    $licenseCodes[] = $licenseCode;
                }

                // Store license codes as JSON array if quantity > 1, otherwise store as string for backward compatibility
                $orderProduct->license_code = $quantity > 1 ? json_encode($licenseCodes) : $licenseCodes[0];
                $orderProduct->save();

                $invoiceItem = $invoiceItems->where('reference_id', $orderProduct->product_id)->where('reference_type', Product::class)->first();
                if ($invoiceItem) {
                    $invoiceItem->options = array_merge($invoiceItem->options, [
                        'license_code' => $orderProduct->license_code,
                    ]);
                    $invoiceItem->save();
                }
            }
        }
    }
}
