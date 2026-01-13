<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
{
    public function toArray($request): array
    {
        // Extract basic filter data
        [$categories, $brands, $tags, $rand, $categoriesRequest, $urlCurrent, $categoryId, $maxFilterPrice] = $this->resource;

        // Check if attribute sets are included
        $attributeSets = $this->resource[8] ?? collect();
        $selectedAttrs = $this->resource[9] ?? [];

        $priceRanges = EcommerceHelper::dataPriceRangesForFilter();

        return [
            'categories' => $categories->map(function ($category) {
                // Extract the slug from the URL
                $urlParts = explode('/', trim($category->url, '/'));
                $slug = end($urlParts);

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $slug,
                    'url' => $category->url,
                    'parent_id' => $category->parent_id,
                ];
            })->all(),
            'brands' => $brands->map(function ($brand) {
                // For brands, we need to extract the slug from the slugable relationship
                $slug = $brand->slugable->key ?? '';

                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => $slug,
                    'url' => $brand->url ?? route('public.products', ['brands[]' => $brand->id]),
                    'products_count' => $brand->products_count,
                ];
            })->all(),
            'tags' => $tags->map(function ($tag) {
                // For tags, we need to extract the slug from the slugable relationship
                $slug = $tag->slugable->key ?? '';

                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $slug,
                    'url' => $tag->url ?? route('public.products', ['tags[]' => $tag->id]),
                    'products_count' => $tag->products_count,
                ];
            })->all(),
            'price_ranges' => $priceRanges,
            'max_price' => $maxFilterPrice,
            'current_category_id' => $categoryId,
            'current_filter_categories' => $categoriesRequest,
            'attributes' => $attributeSets->map(function ($attributeSet) use ($selectedAttrs) {
                $selected = $selectedAttrs[$attributeSet->slug] ?? [];

                return [
                    'id' => $attributeSet->id,
                    'title' => $attributeSet->title,
                    'slug' => $attributeSet->slug,
                    'display_layout' => $attributeSet->display_layout,
                    'attributes' => $attributeSet->attributes->map(function ($attribute) use ($selected) {
                        return [
                            'id' => $attribute->id,
                            'title' => $attribute->title,
                            'slug' => $attribute->slug,
                            'color' => $attribute->color,
                            'image' => $attribute->image ? RvMedia::getImageUrl($attribute->image) : null,
                            'is_default' => $attribute->is_default,
                            'is_selected' => in_array($attribute->id, (array) $selected),
                        ];
                    }),
                ];
            })->all(),
        ];
    }
}
