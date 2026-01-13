<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\ProductCategory;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductCategory
 */
class ProductCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $isUsingUuid = ProductCategory::isUsingStringId();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'icon_image' => $this->icon_image,
            'is_featured' => $this->is_featured,
            'parent_id' => $isUsingUuid ? $this->parent_id : (int) $this->parent_id,
            'slug' => $this->slug,
            'image_with_sizes' => $this->image ? rv_get_image_sizes($this->image, array_unique([
                'origin',
                'thumb',
                ...array_keys(RvMedia::getSizes()),
            ])) : null,
        ];
    }
}
