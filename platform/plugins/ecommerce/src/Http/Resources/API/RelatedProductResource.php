<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class RelatedProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $price = $this->price();
        $thumbnailSize = $request->input('thumbnail_size', 'thumb');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'is_out_of_stock' => $this->isOutOfStock(),
            'stock_status_label' => $this->stock_status_label,
            'stock_status_html' => $this->stock_status_html,
            'price' => $price->getPrice(),
            'price_formatted' => $price->displayAsText(),
            'original_price' => $price->getPriceOriginal(),
            'original_price_formatted' => $price->displayPriceOriginalAsText(),
            'reviews_avg' => $this->reviews_avg,
            'reviews_count' => $this->reviews_count,
            'image_with_sizes' => $this->images ? rv_get_image_list($this->images, array_unique([
                'origin',
                'thumb',
                ...array_keys(RvMedia::getSizes()),
            ])) : null,
            'image_url' => RvMedia::getImageUrl($this->image, $thumbnailSize, false, RvMedia::getDefaultImage()),
            $this->mergeWhen(is_plugin_active('marketplace'), function () {
                $store = $this->original_product->store;

                return [
                    'store' => [
                        'id' => $store?->id,
                        'slug' => $store?->slugable?->key,
                        'name' => $store?->name,
                    ],
                ];
            }),
        ];
    }
}
