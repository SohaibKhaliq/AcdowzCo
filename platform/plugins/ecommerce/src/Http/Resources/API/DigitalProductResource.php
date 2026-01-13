<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\OrderProduct;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderProduct
 */
class DigitalProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $product = $this->product;
        $productFiles = $product->id ? $product->productFiles : $this->productFiles;

        $externalProductFiles = $productFiles->filter(fn ($productFile) => $productFile->is_external_link);
        $internalProductFiles = $productFiles->filter(fn ($productFile) => ! $productFile->is_external_link);

        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_image' => $this->product_image,
            'price' => $this->price,
            'qty' => $this->qty,
            'product' => [
                'id' => $product?->id,
                'name' => $product?->name,
                'description' => $product?->description,
                'image' => $product?->image,
            ],
            'times_downloaded' => $this->times_downloaded,
            'downloaded_at' => $this->downloaded_at ? $this->downloaded_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'has_external_files' => $externalProductFiles->isNotEmpty(),
            'has_internal_files' => $internalProductFiles->isNotEmpty(),
            'external_files_count' => $externalProductFiles->count(),
            'internal_files_count' => $internalProductFiles->count(),
            'download_token' => $this->download_token,
        ];
    }
}
