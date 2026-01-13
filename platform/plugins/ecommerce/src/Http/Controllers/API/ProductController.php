<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Resources\API\AvailableProductResource;
use Botble\Ecommerce\Http\Resources\API\ProductDetailResource;
use Botble\Ecommerce\Http\Resources\API\RelatedProductResource;
use Botble\Ecommerce\Http\Resources\API\ReviewResource;
use Botble\Ecommerce\Http\Resources\ProductVariationResource;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Models\Review;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\Products\GetProductService;
use Botble\Ecommerce\Services\Products\GetProductWithCrossSalesBySlugService;
use Botble\Ecommerce\Services\Products\ProductCrossSalePriceService;
use Botble\Ecommerce\Services\Products\ProductImageService;
use Botble\Ecommerce\Services\Products\UpdateDefaultProductService;
use Botble\Media\Facades\RvMedia;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends BaseApiController
{
    public function __construct(
        protected ProductCrossSalePriceService $productCrossSalePriceService
    ) {
    }

    /**
     * Get list of products
     *
     * @group Products
     * @param Request $request
     * @param GetProductService $productService
     * @queryParam categories string[] Filter by category IDs. No-example
     * @queryParam brands string[] Filter by brand IDs. No-example
     * @queryParam collections string[] Filter by collection IDs. No-example
     * @queryParam q string Search term. No-example
     * @queryParam sort_by string Sort field. Value: default_sorting, date_asc, date_desc, price_asc, price_desc, name_asc, name_desc, rating_asc, rating_desc
     * @queryParam page int The current page. No-example
     * @queryParam per_page int Number of items per page. No-example
     * @queryParam discounted_only boolean Filter by discounted only. No-example
     * @queryParam min_price int Minimum price. No-example
     * @queryParam max_price int Maximum price. No-example
     * @queryParam price_ranges string Price ranges as JSON string. Example: [{"from":10,"to":20},{"from":30,"to":40}]
     * @queryParam attributes string Attributes as JSON string. Example: [{"id":1,"value":1},{"id":2,"value":2}]
     * @queryParam thumbnail_size string Size of product thumbnail images. Value: thumb, small, medium, large. Default: thumb
     *
     * @return JsonResponse
     */
    public function index(Request $request, GetProductService $productService)
    {
        $with = EcommerceHelper::withProductEagerLoadingRelations();

        $products = $productService->getProduct($request, null, null, $with);

        return $this
            ->httpResponse()
            ->setData(AvailableProductResource::collection($products))
            ->toApiResponse();
    }

    /**
     * Get product details by slug
     *
     * @group Products
     * @param string $slug Product slug
     * @queryParam thumbnail_size string Size of product thumbnail images. Value: thumb, small, medium, large. Default: thumb
     * @return JsonResponse
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function show(string $slug, Request $request)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Product::class));

        abort_unless($slug, 404);

        $product = get_products(
            [
                'condition' => [
                    'ec_products.id' => $slug->reference_id,
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                ],
                'take' => 1,
                'with' => [
                    'slugable',
                    'tags',
                    'tags.slugable',
                    'categories',
                    'categories.slugable',
                    'options',
                    'options.values',
                    'crossSales' => function (BelongsToMany $query): void {
                        $query->where('ec_product_cross_sale_relations.is_variant', false);
                    },
                ],
            ]
        );

        abort_unless($product, 404);

        $this->productCrossSalePriceService->applyProduct($product);

        [, $productVariation, $selectedAttrs] = EcommerceHelper::getProductVariationInfo(
            $product,
            $request->input()
        );

        if (! $product->is_variation && $productVariation) {
            $product = app(UpdateDefaultProductService::class)->updateColumns($product, $productVariation);
            $selectedProductVariation = $productVariation->defaultVariation;
            $selectedProductVariation->product_id = $productVariation->id;

            $product->defaultVariation = $selectedProductVariation;

            $product->image = $selectedProductVariation->configurableProduct->image ?: $product->image;
        }

        // Get product variations info for filtering unavailable attributes
        $productVariations = ProductVariation::query()
            ->where('configurable_product_id', $product->id)
            ->with(['productAttributes', 'product'])
            ->get();

        $productVariationsInfo = ProductVariationItem::getVariationsInfo($productVariations->pluck('id')->all());

        if ($productVariationsInfo->isNotEmpty()) {
            $productVariationsInfo = $productVariationsInfo
                ->reject(function (ProductVariationItem $productVariation) use ($productVariations) {
                    $variationItem = $productVariations->where('id', $productVariation->variation_id)->first();

                    if (! $variationItem) {
                        return false;
                    }

                    return $variationItem->product->isOutOfStock();
                });
        }

        // Get attribute sets and attributes
        $attributeSets = $product->productAttributeSets()->oldest('order')->get();
        $productAttributes = app(ProductInterface::class)->getRelatedProductAttributes($product)->sortBy('order');

        $price = $productVariation->price();

        return $this
            ->httpResponse()
            ->setData(new ProductDetailResource($product))
            ->setAdditional([
                'default_product_variation' => [
                    'id' => $productVariation->id,
                    'sku' => $productVariation->sku,
                    'quantity' => $productVariation->quantity,
                    'is_out_of_stock' => $productVariation->isOutOfStock(),
                    'stock_status_label' => $productVariation->stock_status_label,
                    'stock_status_html' => $productVariation->stock_status_html,
                    'price' => $price->getPrice(),
                    'price_formatted' => $price->displayAsText(),
                    'original_price' => $price->getPriceOriginal(),
                    'original_price_formatted' => $price->displayPriceOriginalAsText(),
                    'image_with_sizes' => $productVariation->images ? rv_get_image_list(
                        $productVariation->images,
                        array_unique([
                            'origin',
                            'thumb',
                            ...array_keys(RvMedia::getSizes()),
                        ])
                    ) : null,
                    'weight' => $productVariation->weight,
                    'height' => $productVariation->height,
                    'wide' => $productVariation->wide,
                    'length' => $productVariation->length,
                    'image_url' => RvMedia::getImageUrl(
                        $productVariation->image,
                        'thumb',
                        false,
                        RvMedia::getDefaultImage()
                    ),
                ],
                'attribute_sets' => array_map(function ($set, $key) use ($productVariationsInfo, $attributeSets, $productAttributes, $productVariations) {
                    $variationNextIds = [];

                    if ($key > 0) {
                        for ($i = 0; $i < $key; $i++) {
                            $previousSet = $attributeSets[$i];
                            [$variationNextIds] = handle_next_attributes_in_product(
                                $productAttributes->where('attribute_set_id', $previousSet->id),
                                $productVariationsInfo,
                                $previousSet->id,
                                [],
                                $i,
                                $variationNextIds
                            );
                        }
                    }

                    $setAttributes = $productAttributes->where('attribute_set_id', $set->id)->sortBy('order');

                    return [
                        'id' => $set->id,
                        'title' => $set->title,
                        'slug' => $set->slug,
                        'order' => $set->order,
                        'display_layout' => $set->display_layout,
                        'use_image_from_product_variation' => $set->use_image_from_product_variation,
                        'attributes' => array_values(array_map(function ($attr) use ($set, $productVariations) {
                            return [
                                'id' => $attr->id,
                                'title' => $attr->title,
                                'slug' => $attr->slug,
                                'color' => $attr->color,
                                'image' => $attr->getAttributeImageUrl($set, $productVariations),
                                'order' => $attr->order,
                            ];
                        }, $setAttributes->all())),
                    ];
                }, $attributeSets->all(), array_keys($attributeSets->all())),
                'unavailable_attribute_ids' => $this->getUnavailableAttributeIds($productVariationsInfo, $attributeSets, $productAttributes, $selectedAttrs instanceof Collection ? $selectedAttrs->pluck('id')->toArray() : []),
                'selected_attributes' => array_map(function ($attr) use ($productVariations) {
                    $attributeSet = $attr->productAttributeSet;

                    return [
                        'id' => $attr->id,
                        'attribute_set' => [
                            'id' => $attr->attribute_set_id,
                            'title' => $attributeSet->title,
                            'slug' => $attributeSet->slug,
                            'order' => $attributeSet->order,
                            'display_layout' => $attributeSet->display_layout,
                        ],
                        'title' => $attr->title,
                        'slug' => $attr->slug,
                        'color' => $attr->color,
                        'image' => $attr->getAttributeImageUrl($attributeSet, $productVariations),
                        'order' => $attr->order,
                    ];
                }, $selectedAttrs instanceof Collection ? $selectedAttrs->all() : $selectedAttrs),
            ])
            ->toApiResponse();
    }

    /**
     * Get unavailable attribute IDs for a product
     *
     * @param Collection $productVariationsInfo
     * @param Collection $attributeSets
     * @param Collection $productAttributes
     * @param array $selectedAttributeIds
     * @return array
     */
    protected function getUnavailableAttributeIds(Collection $productVariationsInfo, Collection $attributeSets, Collection $productAttributes, array $selectedAttributeIds = []): array
    {
        $unavailableAttributeIds = [];
        $variationNextIds = [];

        foreach ($attributeSets as $key => $set) {
            $variationInfo = $productVariationsInfo;

            if ($key != 0) {
                $variationInfo = $productVariationsInfo
                    ->where('attribute_set_id', $set->id)
                    ->whereIn('variation_id', $variationNextIds);
            }

            foreach ($productAttributes->where('attribute_set_id', $set->id) as $attribute) {
                // Skip if this is a selected attribute
                if (in_array($attribute->id, $selectedAttributeIds)) {
                    continue;
                }

                if ($variationInfo->where('id', $attribute->id)->isEmpty()) {
                    $unavailableAttributeIds[] = $attribute->id;
                }
            }

            // Calculate next variation IDs for the next attribute set
            foreach ($productAttributes->where('attribute_set_id', $set->id) as $attribute) {
                // Only consider selected attributes for this set when calculating next variations
                if (in_array($attribute->id, $selectedAttributeIds)) {
                    $variationIds = $productVariationsInfo
                        ->where('attribute_set_id', $set->id)
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

            // If no attributes are selected for this set, use all variations
            if ($key == 0 && empty($variationNextIds)) {
                $variationNextIds = $productVariationsInfo->pluck('variation_id')->unique()->toArray();
            }
        }

        return $unavailableAttributeIds;
    }

    /**
     * Get related products
     *
     * @group Products
     *
     * @return JsonResponse
     */
    public function relatedProducts(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Product::class));

        abort_unless($slug, 404);

        /**
         * @var Product $product
         */
        $product = Product::query()
            ->where('id', $slug->reference_id)
            ->wherePublished()
            ->firstOrFail();

        $relatedProductIds = get_related_products($product);

        return $this
            ->httpResponse()
            ->setData(RelatedProductResource::collection($relatedProductIds))
            ->toApiResponse();
    }

    /**
     * Get product's reviews
     *
     * @group Products
     *
     * @return JsonResponse
     */
    public function reviews(string $slug, Request $request)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Product::class));

        abort_unless($slug, 404);

        /**
         * @var Product $product
         */
        $product = Product::query()
            ->where('id', $slug->reference_id)
            ->wherePublished()
            ->firstOrFail();

        $star = $request->integer('star');
        $perPage = $request->integer('per_page', 10);
        $search = $request->string('search', '');
        $sortBy = $request->string('sort_by', 'newest');

        $reviews = EcommerceHelper::getProductReviews($product, $star, $perPage, $search, $sortBy);

        // Check if the current user has reviewed this product
        $hasReviewed = false;
        $userReview = null;
        $user = $request->user();

        if ($user) {
            // Get the user's review if it exists
            $userReview = Review::getUserReview($user->id, $product->id);
            $hasReviewed = $userReview !== null;
        }

        if ($star) {
            $message = __(':total review(s) ":star star" for ":product"', [
                'total' => $reviews->total(),
                'product' => $product->name,
                'star' => $star,
            ]);
        } else {
            $message = __(':total review(s) for ":product"', [
                'total' => $reviews->total(),
                'product' => $product->name,
            ]);
        }

        $data = [
            'reviews' => ReviewResource::collection($reviews),
            'has_reviewed' => $hasReviewed,
            'user_review' => $hasReviewed ? new ReviewResource($userReview) : null,
        ];

        return $this
            ->httpResponse()
            ->setData($data)
            ->setMessage($message)
            ->toApiResponse();
    }

    /**
     * Get product variation by attributes
     *
     * @group Products
     * @param int|string $id Product ID
     * @param Request $request
     * @param ProductInterface $productRepository
     * @param GetProductWithCrossSalesBySlugService $getProductWithCrossSalesBySlugService
     *
     * @queryParam attributes string[] Array of attribute IDs. Example: 1,2
     * @queryParam reference_product string Reference product slug. No-example
     */
    public function getProductVariation(
        int|string $id,
        Request $request,
        ProductInterface $productRepository,
        GetProductWithCrossSalesBySlugService $getProductWithCrossSalesBySlugService,
    ) {
        $request->validate([
            'reference_product' => ['sometimes', 'required', 'string'],
            'attributes' => ['sometimes', 'array'],
            'attributes.*' => ['required'],
        ]);

        $product = null;

        if ($attributes = $request->input('attributes', [])) {
            $variation = ProductVariation::getVariationByAttributes($id, $attributes);

            if ($variation) {
                $product = $productRepository->getProductVariations($id, [
                    'condition' => [
                        'ec_product_variations.id' => $variation->getKey(),
                        'original_products.status' => BaseStatusEnum::PUBLISHED,
                    ],
                    'select' => [
                        'ec_products.id',
                        'ec_products.name',
                        'ec_products.quantity',
                        'ec_products.price',
                        'ec_products.sale_price',
                        'ec_products.sale_type',
                        'ec_products.start_date',
                        'ec_products.end_date',
                        'ec_products.allow_checkout_when_out_of_stock',
                        'ec_products.with_storehouse_management',
                        'ec_products.stock_status',
                        'ec_products.images',
                        'ec_products.sku',
                        'ec_products.barcode',
                        'ec_products.description',
                        'ec_products.is_variation',
                        'original_products.images as original_images',
                        'ec_products.height',
                        'ec_products.weight',
                        'ec_products.wide',
                        'ec_products.length',
                    ],
                    'take' => 1,
                ]);
            }
        } else {
            $product = Product::query()
                ->where('id', $id)
                ->wherePublished()
                ->select([
                    'id',
                    'name',
                    'quantity',
                    'price',
                    'sale_price',
                    'allow_checkout_when_out_of_stock',
                    'with_storehouse_management',
                    'stock_status',
                    'images',
                    'sku',
                    'description',
                    'is_variation',
                    'height',
                    'weight',
                    'wide',
                    'length',
                ])
                ->first();

            $attributes = $product ? $product->defaultVariation->productAttributes->pluck('id')->all() : [];
        }

        if ($product) {
            $imageData = app(ProductImageService::class)->getProductImagesWithSizes($product);
            $product->image_with_sizes = $imageData['image_with_sizes'];

            if ($product->stock_status == 'on_backorder') {
                $product->warningMessage = __('Warning: This product is on backorder and may take longer to ship.');
            } elseif ($product->isOutOfStock()) {
                $product->errorMessage = __('Out of stock');
            } elseif (! $product->with_storehouse_management || $product->quantity < 1) {
                $product->successMessage = __('In stock');
            } elseif ($product->quantity) {
                if (EcommerceHelper::showNumberOfProductsInProductSingle()) {
                    if ($product->quantity != 1) {
                        $product->successMessage = __(':number products available', ['number' => $product->quantity]);
                    } else {
                        $product->successMessage = __(':number product available', ['number' => $product->quantity]);
                    }
                } else {
                    $product->successMessage = __('In stock');
                }
            }

            $originalProduct = $product->original_product;
        } else {
            $originalProduct = Product::query()
                ->where('id', $id)
                ->wherePublished()
                ->select([
                    'id',
                    'name',
                    'quantity',
                    'price',
                    'sale_price',
                    'allow_checkout_when_out_of_stock',
                    'with_storehouse_management',
                    'stock_status',
                    'images',
                    'sku',
                    'description',
                    'is_variation',
                    'height',
                    'weight',
                    'wide',
                    'length',
                ])
                ->first();

            if ($originalProduct) {
                if ($originalProduct->images) {
                    $originalProduct->image_with_sizes = rv_get_image_list($originalProduct->images, array_unique([
                        'origin',
                        'thumb',
                        ...array_keys(RvMedia::getSizes()),
                    ]));
                }

                $originalProduct->errorMessage = __('Please select attributes');
            }
        }

        if (! $originalProduct) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Not available'));
        }

        $productAttributes = $productRepository->getRelatedProductAttributes($originalProduct)->sortBy('order');

        $attributeSets = $originalProduct->productAttributeSets()->orderBy('order')->get();

        $productVariations = ProductVariation::query()
            ->where('configurable_product_id', $originalProduct->id)
            ->get();

        $productVariationsInfo = ProductVariationItem::getVariationsInfo($productVariations->pluck('id')->all());

        if ($productVariationsInfo->isNotEmpty()) {
            $productVariationsInfo = $productVariationsInfo
                ->reject(function (ProductVariationItem $productVariation) use ($productVariations) {
                    $variationItem = $productVariations->where('id', $productVariation->variation_id)->first();

                    if (! $variationItem) {
                        return false;
                    }

                    return $variationItem->product->isOutOfStock();
                });
        }

        $variationInfo = $productVariationsInfo;

        $unavailableAttributeIds = [];
        $variationNextIds = [];
        foreach ($attributeSets as $key => $set) {
            if ($key != 0) {
                $variationInfo = $productVariationsInfo
                    ->where('attribute_set_id', $set->id)
                    ->whereIn('variation_id', $variationNextIds);
            }

            [$variationNextIds, $unavailableAttributeIds] = handle_next_attributes_in_product(
                $productAttributes->where('attribute_set_id', $set->id),
                $productVariationsInfo,
                $set->id,
                $attributes,
                $key,
                $variationNextIds,
                $variationInfo,
                $unavailableAttributeIds
            );
        }

        if (! $product) {
            $product = $originalProduct;
        }

        if (! $product->is_variation) {
            $selectedAttributes = $product->defaultVariation->productAttributes->map(function ($item) {
                $item->attribute_set_slug = $item->productAttributeSet->slug;

                return $item;
            });
        } else {
            $selectedAttributes = $product->variationProductAttributes;

            if ($attributes) {
                $selectedAttributes = $selectedAttributes->whereIn('id', $attributes);
            }
        }

        $product->unavailableAttributeIds = $unavailableAttributeIds;
        $product->selectedAttributes = $selectedAttributes;

        if (
            $request->filled('reference_product')
            && $referenceProduct = $getProductWithCrossSalesBySlugService->handle(
                $request->input('reference_product')
            )
        ) {
            app(ProductCrossSalePriceService::class)->applyProduct($referenceProduct);
        }

        return $this
            ->httpResponse()
            ->setData(new ProductVariationResource($product))
            ->setAdditional([
                'attribute_sets' => array_map(function ($set, $key) use ($productVariationsInfo, $attributeSets, $productAttributes, $productVariations) {
                    $variationNextIds = [];

                    if ($key > 0) {
                        for ($i = 0; $i < $key; $i++) {
                            $previousSet = $attributeSets[$i];
                            [$variationNextIds] = handle_next_attributes_in_product(
                                $productAttributes->where('attribute_set_id', $previousSet->id),
                                $productVariationsInfo,
                                $previousSet->id,
                                [],
                                $i,
                                $variationNextIds
                            );
                        }
                    }

                    $setAttributes = $productAttributes->where('attribute_set_id', $set->id)->sortBy('order');

                    return [
                        'id' => $set->id,
                        'title' => $set->title,
                        'slug' => $set->slug,
                        'order' => $set->order,
                        'display_layout' => $set->display_layout,
                        'use_image_from_product_variation' => $set->use_image_from_product_variation,
                        'attributes' => array_values(array_map(function ($attr) use ($set, $productVariations) {
                            return [
                                'id' => $attr->id,
                                'title' => $attr->title,
                                'slug' => $attr->slug,
                                'color' => $attr->color,
                                'image' => method_exists($attr, 'getAttributeImageUrl')
                                    ? $attr->getAttributeImageUrl($set, $productVariations)
                                    : ($attr->image ?? null),
                                'order' => $attr->order,
                            ];
                        }, $setAttributes->all())),
                    ];
                }, $attributeSets->all(), array_keys($attributeSets->all())),
                'unavailable_attribute_ids' => $unavailableAttributeIds,
                'selected_attributes' => is_object($selectedAttributes) && method_exists($selectedAttributes, 'map')
                    ? $selectedAttributes->map(function ($attr) use ($productVariations) {
                        if (! is_object($attr)) {
                            return null;
                        }

                        $attributeSet = $attr->productAttributeSet ?? null;

                        return [
                            'id' => $attr->id ?? null,
                            'attribute_set' => $attributeSet ? [
                                'id' => $attr->attribute_set_id ?? null,
                                'title' => $attributeSet->title ?? null,
                                'slug' => $attributeSet->slug ?? null,
                                'order' => $attributeSet->order ?? 0,
                                'display_layout' => $attributeSet->display_layout ?? null,
                            ] : null,
                            'title' => $attr->title ?? null,
                            'slug' => $attr->slug ?? null,
                            'color' => $attr->color ?? null,
                            'image' => method_exists($attr, 'getAttributeImageUrl')
                                ? $attr->getAttributeImageUrl($attributeSet, $productVariations)
                                : ($attr->image ?? null),
                            'order' => $attr->order ?? 0,
                        ];
                    })->filter()->values()->all()
                    : [],
            ])
            ->toApiResponse();
    }

    /**
     * Get cross-sale products for a product
     *
     * @group Products
     * @param string $slug Product slug
     * @param ProductCrossSalePriceService $productCrossSalePriceService
     * @return JsonResponse
     */
    public function getCrossSaleProducts(string $slug, ProductCrossSalePriceService $productCrossSalePriceService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Product::class));

        abort_unless($slug, 404);

        /**
         * @var Product $product
         */
        $product = Product::query()
            ->where('id', $slug->reference_id)
            ->wherePublished()
            ->firstOrFail();

        // Load cross-sale products
        $product->loadMissing('crossSales');

        // Get cross-sale products using the helper function
        $crossSaleProducts = get_cross_sale_products($product, null, [
            'slugable',
            'productLabels',
            'productCollections',
            'variationInfo',
            'defaultVariation',
            'defaultVariation.configurableProduct',
            'defaultVariation.productAttributes',
            'productAttributeSets',
            'variationProductAttributes',
        ]);

        // Apply cross-sale pricing
        $productCrossSalePriceService->applyProduct($product);

        // Pass the parent product as additional data to the resource collection
        $resourceCollection = RelatedProductResource::collection($crossSaleProducts)
            ->additional(['parent_product' => $product]);

        return $this
            ->httpResponse()
            ->setData($resourceCollection)
            ->toApiResponse();
    }
}
