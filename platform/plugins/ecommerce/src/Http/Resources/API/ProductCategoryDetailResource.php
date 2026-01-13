<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductCategory
 */
class ProductCategoryDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon ? BaseHelper::renderIcon($this->icon) : null,
            'description' => $this->description,
            'icon_image' => $this->icon_image ? RvMedia::getImageUrl($this->icon_image) : null,
            'is_featured' => $this->is_featured,
            'parent_id' => $this->parent_id,
            'slug' => $this->slug,
            'image_with_sizes' => $this->image ? rv_get_image_sizes($this->image, array_unique([
                'origin',
                'thumb',
                ...array_keys(RvMedia::getSizes()),
            ])) : null,
        ];
    }
}
