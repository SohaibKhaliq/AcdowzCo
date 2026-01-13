<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductCollection;
use Botble\Ecommerce\Models\ProductView;
use Botble\Ecommerce\Models\Review;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\ProductCacheService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

if (! function_exists('get_product_by_id')) {
    function get_product_by_id(int|string $productId): ?Product
    {
        /**
         * @var ?Product $product
         */
        $product = Product::query()->find($productId);

        return $product;
    }
}

if (! function_exists('get_products')) {
    function get_products(array $params = [], array $filters = []): Collection|LengthAwarePaginator|Product|null
    {
        $params = [
            'condition' => [
                'ec_products.is_variation' => 0,
            ],
            'order_by' => [
                'ec_products.order' => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'take' => null,
            'paginate' => [
                'per_page' => null,
                'current_paged' => 1,
            ],
            'select' => [
                'ec_products.*',
            ],
            'with' => ['slugable'],
            'withCount' => [],
            'withAvg' => [],
            ...$params,
        ];

        return app(ProductInterface::class)->getProducts($params, $filters);
    }
}

if (! function_exists('get_products_on_sale')) {
    function get_products_on_sale(array $params = []): Collection|LengthAwarePaginator|Product|null
    {
        $params = array_merge([
            'condition' => [
                'ec_products.is_variation' => 0,
            ],
            'order_by' => [
                'ec_products.order' => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'take' => null,
            'paginate' => [
                'per_page' => null,
                'current_paged' => 1,
            ],
            'select' => [
                'ec_products.*',
            ],
            'with' => [],
            'withCount' => [],
        ], $params);

        return app(ProductInterface::class)->getOnSaleProducts($params);
    }
}

if (! function_exists('get_featured_products')) {
    function get_featured_products(array $params = []): Collection|LengthAwarePaginator|Product|null
    {
        $params = array_merge([
            'condition' => [
                'ec_products.is_featured' => 1,
                'ec_products.is_variation' => 0,
            ],
            'take' => null,
            'order_by' => [
                'ec_products.order' => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'select' => ['ec_products.*'],
            'with' => [],
        ], $params);

        return app(ProductInterface::class)->getProducts($params);
    }
}

if (! function_exists('get_top_rated_products')) {
    function get_top_rated_products(int $limit = 10, array $with = [], array $withCount = []): Collection|LengthAwarePaginator|Product|null
    {
        if (! EcommerceHelper::isReviewEnabled()) {
            return collect();
        }

        $topProductIds = get_top_rated_product_ids($limit);

        return get_products([
                'condition' => [
                    ['ec_products.id', 'IN', $topProductIds],
                    'ec_products.is_variation' => 0,
                ],
                'order_by' => [
                    'reviews_avg' => 'DESC',
                    'ec_products.order' => 'ASC',
                    'ec_products.created_at' => 'DESC',
                ],
                'take' => null,
                'paginate' => [
                    'per_page' => null,
                    'current_paged' => 1,
                ],
                'select' => [
                    'ec_products.*',
                ],
                'with' => $with,
                'withCount' => $withCount,
            ]);
    }
}

if (! function_exists('get_top_rated_product_ids')) {
    function get_top_rated_product_ids(int $limit = 10): array
    {
        return Review::query()
            ->wherePublished()
            ->selectRaw('product_id, avg(star) AS star')
            ->groupBy('product_id')
            ->latest('star')
            ->limit($limit)
            ->pluck('product_id')
            ->all();
    }
}

if (! function_exists('get_trending_product_ids')) {
    function get_trending_product_ids(int $limit = 10, int $days = 7): array
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        return ProductView::query()
            ->select('product_id')
            ->selectRaw('SUM(views) as total_views')
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->groupBy('product_id')
            ->orderByDesc('total_views')
            ->limit($limit)
            ->pluck('product_id')
            ->toArray();
    }
}

if (! function_exists('get_trending_products')) {
    function get_trending_products(array $params = [], ?int $days = null): Collection|LengthAwarePaginator|Product|null
    {
        if ($days === null) {
            $days = (int) get_ecommerce_setting('trending_products_period_days', 7);
        }

        $limit = $params['take'] ?? 10;
        $cacheKey = 'trending_products_' . $limit . '_' . $days . '_' . app()->getLocale();
        $cacheService = app(ProductCacheService::class);
        $cacheTtl = 3600;

        return $cacheService->remember($cacheKey, $cacheTtl, function () use ($params, $days) {
            $trendingIds = get_trending_product_ids($params['take'] ?? 10, $days);

            if (empty($trendingIds)) {
                $params = array_merge([
                    'condition' => [
                        'ec_products.is_variation' => 0,
                    ],
                    'take' => 10,
                    'order_by' => [
                        'ec_products.views' => 'DESC',
                    ],
                    'select' => ['ec_products.*'],
                    'with' => [],
                ], $params);

                return app(ProductInterface::class)->getProducts($params);
            }

            $params = array_merge([
                'condition' => [
                    ['ec_products.id', 'IN', $trendingIds],
                    'ec_products.is_variation' => 0,
                ],
                'take' => null,
                'select' => ['ec_products.*'],
                'with' => [],
            ], $params);

            $products = app(ProductInterface::class)->getProducts($params);

            if ($products instanceof Collection) {
                $sortedProducts = collect($trendingIds)->map(function ($id) use ($products) {
                    return $products->firstWhere('id', $id);
                })->filter()->values();

                return $sortedProducts;
            }

            return $products;
        });
    }
}

if (! function_exists('get_featured_product_categories')) {
    function get_featured_product_categories(): Collection|LengthAwarePaginator
    {
        return ProductCategory::query()
            ->where('is_featured', true)
            ->wherePublished()
            ->oldest('order')->latest()
            ->with('slugable')
            ->get();
    }
}

if (! function_exists('get_product_collections')) {
    function get_product_collections(
        array $condition = [],
        array $with = [],
        array $select = ['*']
    ): Collection {
        return ProductCollection::query()
            ->where($condition)
            ->wherePublished()
            ->select($select)
            ->with($with)
            ->get();
    }
}

if (! function_exists('get_products_by_collections')) {
    function get_products_by_collections(array $params = []): Collection
    {
        return app(ProductInterface::class)->getProductsByCollections($params);
    }
}

if (! function_exists('get_default_product_variation')) {
    function get_default_product_variation(int|string $configurableId): ?Product
    {
        return app(ProductInterface::class)
            ->getProductVariations($configurableId, [
                'condition' => [
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                    'ec_products.is_variation' => 1,
                ],
                'take' => 1,
                'order_by' => [
                    'ec_product_variations.is_default' => 'DESC',
                ],
            ]);
    }
}

if (! function_exists('get_product_by_brand')) {
    function get_product_by_brand(array $params): Collection|LengthAwarePaginator|Product|null
    {
        return app(ProductInterface::class)->getProductByBrands($params);
    }
}

if (! function_exists('the_product_price')) {
    function the_product_price(Product $product, array $htmlWrap = []): string
    {
        $htmlWrapParams = array_merge([
            'open_wrap_price' => '<del>',
            'close_wrap_price' => '</del>',
            'open_wrap_sale' => '<ins>',
            'close_wrap_sale' => '</ins>',
        ], $htmlWrap);

        if ($product->front_sale_price !== $product->price) {
            return $htmlWrapParams['open_wrap_price'] . format_price($product->price) . $htmlWrapParams['close_wrap_price'] .
                $htmlWrapParams['open_wrap_sale'] . format_price($product->front_sale_price) . $htmlWrapParams['close_wrap_sale'];
        }

        return $htmlWrapParams['open_wrap_sale'] . $product->price . $htmlWrapParams['close_wrap_sale'];
    }
}

if (! function_exists('get_related_products')) {
    function get_related_products(Product $product, ?int $limit = null): Collection|LengthAwarePaginator|Product|null
    {
        if (! EcommerceHelper::isEnabledRelatedProducts()) {
            return new EloquentCollection();
        }

        $limit = $limit ?: theme_option('number_of_related_product', 4);

        $cacheKey = 'related_products_' . $product->getKey() . '_' . $limit . '_' . app()->getLocale();
        $cacheService = app(ProductCacheService::class);

        return $cacheService->remember($cacheKey, 1800, function () use ($product, $limit) {
            $params = [
                'condition' => [
                    'ec_products.is_variation' => 0,
                ],
                'order_by' => [
                    'ec_products.order' => 'ASC',
                    'ec_products.created_at' => 'DESC',
                ],
                'take' => (int) $limit,
                'select' => [
                    'ec_products.*',
                ],
                'with' => EcommerceHelper::withProductEagerLoadingRelations(),
            ];

            $relatedIds = $product->products()->allRelatedIds()->toArray();

            $filters = [];

            if (! empty($relatedIds)) {
                $params['condition'][] = ['ec_products.id', 'IN', $relatedIds];
            } else {
                $params['condition'][] = ['ec_products.id', '!=', $product->getKey()];

                $relatedProductsSource = get_ecommerce_setting('related_products_source', 'category');

                if ($relatedProductsSource === 'brand' && $product->brand_id) {
                    $filters = ['brands' => [$product->brand_id]];
                } else {
                    $filters = ['categories' => $product->categories()->pluck('ec_product_categories.id')->all()];
                }
            }

            return app(ProductInterface::class)->filterProducts($filters, $params);
        });
    }
}

if (! function_exists('get_cross_sale_products')) {
    function get_cross_sale_products(Product $product, ?int $limit = null, array $with = []): EloquentCollection
    {
        $limit = $limit ?: theme_option('number_of_cross_sale_product', 4);

        $cacheKey = 'cross_sale_products_' . $product->getKey() . '_' . $limit . '_' . md5(json_encode($with)) . '_' . app()->getLocale();
        $cacheService = app(ProductCacheService::class);

        return $cacheService->remember($cacheKey, 1800, function () use ($product, $limit, $with) {
            $with = array_merge(EcommerceHelper::withProductEagerLoadingRelations(), $with);

            /**
             * @phpstan-ignore-next-line
             */
            return $product
                ->crossSales()
                ->limit((int) $limit)
                ->with($with)
                ->wherePublished()
                ->notOutOfStock()
                ->get();
        });
    }
}

if (! function_exists('get_up_sale_products')) {
    function get_up_sale_products(Product $product, int $limit = 4, array $with = []): EloquentCollection
    {
        $with = array_merge(EcommerceHelper::withProductEagerLoadingRelations(), $with);

        /**
         * @phpstan-ignore-next-line
         */
        return $product
            ->upSales()
            ->limit($limit)
            ->with($with)
            ->wherePublished()
            ->notOutOfStock()
            ->get();
    }
}

if (! function_exists('get_cart_cross_sale_products')) {
    function get_cart_cross_sale_products(array $productIds, int $limit = 4, array $with = []): Collection|LengthAwarePaginator|Product|null
    {
        $crossSaleIds = DB::table('ec_product_cross_sale_relations')
            ->whereIn('from_product_id', $productIds)
            ->pluck('to_product_id')
            ->all();

        $params = [
            'condition' => [
                ['ec_products.id', 'IN', $crossSaleIds],
                'ec_products.is_variation' => 0,
            ],
            'order_by' => [
                'ec_products.order' => 'ASC',
                'ec_products.created_at' => 'DESC',
            ],
            'take' => $limit,
            'select' => [
                'ec_products.*',
            ],
            'with' => array_merge(EcommerceHelper::withProductEagerLoadingRelations(), $with),
        ];

        return app(ProductInterface::class)->getProducts($params);
    }
}

if (! function_exists('get_product_attributes_with_set')) {
    function get_product_attributes_with_set(Product $product, int|string $setId): array
    {
        $productAttributes = app(ProductInterface::class)->getRelatedProductAttributes($product);

        $attributes = [];

        foreach ($productAttributes as $attribute) {
            if ($attribute->attribute_set_id === $setId) {
                $attributes[] = $attribute;
            }
        }

        return $attributes;
    }
}

if (! function_exists('handle_next_attributes_in_product')) {
    function handle_next_attributes_in_product(
        Collection $productAttributes,
        Collection $productVariationsInfo,
        int|string|null $setId,
        array $selectedAttributes,
        ?string $key,
        array $variationNextIds,
        ?Collection $variationInfo = null,
        array $unavailableAttributeIds = []
    ): array {
        foreach ($productAttributes as $attribute) {
            if ($variationInfo != null && $variationInfo->where('id', $attribute->id)->isEmpty()) {
                $unavailableAttributeIds[] = $attribute->id;
            }

            if (in_array($attribute->id, $selectedAttributes)) {
                $variationIds = $productVariationsInfo
                    ->where('attribute_set_id', $setId)
                    ->where('id', $attribute->id)
                    ->pluck('variation_id')
                    ->toArray();

                if ($key == 0) {
                    $variationNextIds = $variationIds;
                } else {
                    $variationNextIds = array_intersect($variationNextIds, $variationIds);
                }
            }
        }

        return [$variationNextIds, $unavailableAttributeIds];
    }
}
