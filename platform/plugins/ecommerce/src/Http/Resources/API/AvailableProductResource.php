<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;
use Botble\Shortcode\Facades\Shortcode;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class AvailableProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $price = $this->price();
        $thumbnailSize = $request->input('thumbnail_size', 'thumb');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'url' => $this->url,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => Shortcode::compile((string) $this->description, true)->toHtml(),
            'content' => Shortcode::compile((string) $this->content, true)->toHtml(),
            'with_storehouse_management' => (bool) $this->with_storehouse_management,
            'quantity' => (int) $this->quantity,
            'is_out_of_stock' => $this->isOutOfStock(),
            'stock_status_label' => $this->stock_status_label,
            'stock_status_html' => $this->stock_status_html,
            'price' => $price->getPrice(),
            'price_formatted' => $price->displayAsText(),
            'original_price' => $price->getPriceOriginal(),
            'original_price_formatted' => $price->displayPriceOriginalAsText(),
            'reviews_avg' => (float) $this->reviews_avg,
            'reviews_count' => (int) $this->reviews_count,
            'images' => $this->images ? array_map(function ($image) {
                return RvMedia::getImageUrl($image);
            }, $this->images) : [],
            'images_thumb' => $this->images ? array_map(function ($image) {
                return RvMedia::getImageUrl($image, 'thumb');
            }, $this->images) : [],
            'image_with_sizes' => $this->images ? rv_get_image_list($this->images, array_unique([
                'origin',
                'thumb',
                ...array_keys(RvMedia::getSizes()),
            ])) : null,
            'weight' => $this->weight,
            'height' => $this->height,
            'wide' => $this->wide,
            'length' => $this->length,
            'image_url' => RvMedia::getImageUrl($this->image, $thumbnailSize, false, RvMedia::getDefaultImage()),
            'product_options' => $this->when(! $this->is_variation, function () {
                return ProductOptionResource::collection($this->original_product->options);
            }),
            $this->when($this->is_variation, function () {
                return [
                    'variation_attributes' => $this->variation_attributes,
                ];
            }),
            'store' => $this->when(is_plugin_active('marketplace'), function () {
                $store = $this->original_product->store;

                return [
                    'id' => $store?->id,
                    'slug' => $store?->slugable?->key,
                    'name' => $store?->name,
                ];
            }),
        ];
    }
}
