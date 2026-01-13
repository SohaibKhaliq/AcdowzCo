<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Botble\Ecommerce\Models\Product;
use Botble\Shortcode\Facades\Shortcode;

/**
 * @mixin Product
 */
class ProductDetailResource extends AvailableProductResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        $data['content'] = Shortcode::compile((string) $this->content, true)->toHtml();

        if (is_plugin_active('marketplace')) {
            $store = $this->original_product->store;

            if ($store) {
                $totalReviews = $store->reviews()->count();
                $positiveReviews = $store->reviews()->where('ec_reviews.star', '>=', 4)->count();
                $positiveRating = $totalReviews > 0 ? round(($positiveReviews / $totalReviews) * 100) : 0;

                $data['store'] = [
                    'id' => $store->id,
                    'slug' => $store->slugable?->key,
                    'name' => $store->name,
                    'reviews_avg' => (float) round($store->reviews()->avg('star'), 2),
                    'reviews_count' => (int) $totalReviews,
                    'positive_rating' => (float) $positiveRating,
                ];
            }
        }

        return [
            ...$data,
            'brand' => $this->brand ? [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ] : null,
            'categories' => $this->categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ];
            }),
            'sale_percent' => $this->sale_percent,
        ];
    }
}
