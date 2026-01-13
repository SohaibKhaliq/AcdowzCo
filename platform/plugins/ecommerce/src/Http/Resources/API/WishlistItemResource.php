<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Cart\CartItem;
use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CartItem
 */
class WishlistItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $product = Product::query()->find($this->id);

        if (! $product) {
            return [];
        }

        // Format name with store if marketplace is active
        $name = $product->name;
        if (is_plugin_active('marketplace') && $product->original_product->store_id && $product->original_product->store->name) {
            $name .= ' (' . $product->original_product->store->name . ')';
        }

        // Get image URL
        $imageUrl = isset($this->options['image'])
            ? RvMedia::getImageUrl($this->options['image'], 'thumb', false, RvMedia::getDefaultImage())
            : RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage());

        $price = $product->price();

        // Base data that includes all fields from AvailableProductResource
        $data = [
            // Cart item specific fields
            'id' => $this->id,
            'rowId' => $this->rowId,

            // Product fields from AvailableProductResource
            'name' => $name,
            'sku' => $product->sku,
            'description' => $product->description,
            'slug' => $product->slug,
            'with_storehouse_management' => $product->with_storehouse_management,
            'quantity' => $product->quantity,
            'is_out_of_stock' => $product->isOutOfStock(),
            'stock_status_label' => $product->stock_status_label,
            'stock_status_html' => $product->stock_status_html,
            'price' => $price->getPrice(),
            'price_formatted' => $price->displayAsText(),
            'original_price' => $price->getPriceOriginal(),
            'original_price_formatted' => $price->displayPriceOriginalAsText(),
            'total_taxes_percentage' => $product->total_taxes_percentage,
            'reviews_avg' => $product->reviews_avg,
            'reviews_count' => $product->reviews_count,
            'image_with_sizes' => $product->image_with_sizes,
            'weight' => $product->weight,
            'height' => $product->height,
            'wide' => $product->wide,
            'length' => $product->length,
            'image_url' => $imageUrl,
            'is_variation' => $product->is_variation,
            'original_product_id' => $product->original_product->id,
        ];

        // Add product options
        $data['product_options'] = ProductOptionResource::collection(
            $product->is_variation ? $product->original_product->options : $product->options
        );

        // Add variation attributes if this is a variation
        if ($product->is_variation) {
            $data['variation_attributes'] = $product->variation_attributes;
        }

        // Add store information if marketplace plugin is active
        if (is_plugin_active('marketplace') && $product->original_product->store_id) {
            $store = $product->original_product->store;
            if ($store) {
                $data['store_id'] = $store->id;
                $data['store'] = [
                    'id' => $store->id,
                    'name' => $store->name,
                    'slug' => $store->slugable->key ?? '',
                    'logo' => RvMedia::getImageUrl($store->logo, null, false, RvMedia::getDefaultImage()),
                ];
            }
        }

        return $data;
    }
}
