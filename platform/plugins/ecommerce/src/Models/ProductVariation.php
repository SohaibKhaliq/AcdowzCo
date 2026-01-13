<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Services\Products\UpdateDefaultProductService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends BaseModel
{
    protected $table = 'ec_product_variations';

    protected $fillable = [
        'product_id',
        'configurable_product_id',
        'is_default',
    ];

    public $timestamps = false;

    protected static function booted(): void
    {
        self::created(function (ProductVariation $variation): void {
            if ($variation->configurable_product_id) {
                Product::query()
                    ->where('id', $variation->configurable_product_id)
                    ->update([
                        'variations_count' => ProductVariation::query()
                            ->where('configurable_product_id', $variation->configurable_product_id)
                            ->count(),
                    ]);
            }
        });

        self::deleted(function (ProductVariation $variation): void {
            $variation->productAttributes()->detach();
            $variation->variationItems()->delete();

            if ($variation->product && $variation->product->is_variation) {
                $variation->product->delete();
                event(new DeletedContentEvent(PRODUCT_MODULE_SCREEN_NAME, request(), $variation->product));
            }

            if ($variation->configurable_product_id) {
                Product::query()
                    ->where('id', $variation->configurable_product_id)
                    ->update([
                        'variations_count' => ProductVariation::query()
                            ->where('configurable_product_id', $variation->configurable_product_id)
                            ->count(),
                    ]);
            }
        });

        self::updated(function (ProductVariation $variation): void {
            if ($variation->is_default) {
                app(UpdateDefaultProductService::class)->execute($variation->product);

                ProductQuantityUpdatedEvent::dispatch($variation->product);
            }
        });
    }

    public function variationItems(): HasMany
    {
        return $this->hasMany(ProductVariationItem::class, 'variation_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    public function configurableProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'configurable_product_id')->withDefault();
    }

    public function productAttributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'ec_product_variation_items',
            'variation_id',
            'attribute_id'
        );
    }

    public function productVariationItems(): HasMany
    {
        return $this->hasMany(ProductVariationItem::class, 'variation_id');
    }

    public static function getVariationByAttributes(int|string $configurableProductId, array $attributes)
    {
        $attributes = array_unique($attributes);

        return self::query()
            ->whereNotNull('product_id')
            ->where('configurable_product_id', $configurableProductId)
            ->whereHas('variationItems', function ($query) use ($attributes): void {
                $query->whereIn('attribute_id', $attributes);
            }, '=', count($attributes))
            ->with('variationItems')
            ->first();
    }

    public static function getVariationByAttributesOrCreate(int|string $configurableProductId, array $attributes): array
    {
        $variation = self::getVariationByAttributes($configurableProductId, $attributes);

        if (! $variation) {
            $variation = self::query()->create([
                'configurable_product_id' => $configurableProductId,
            ]);

            new CreatedContentEvent(PRODUCT_VARIATIONS_MODULE_SCREEN_NAME, request(), $variation);

            foreach ($attributes as $attribute) {
                ProductVariationItem::query()->create([
                    'attribute_id' => $attribute,
                    'variation_id' => $variation->getKey(),
                ]);
            }

            return [
                'variation' => $variation,
                'created' => true,
            ];
        }

        return [
            'variation' => $variation,
            'created' => false,
        ];
    }

    public static function correctVariationItems($configurableProductId, array $attributes)
    {
        if (! $attributes) {
            $attributes = [0];
        }

        $items = ProductVariationItem::query()
            ->join(
                'ec_product_variations',
                'ec_product_variations.id',
                '=',
                'ec_product_variation_items.variation_id'
            )
            ->whereRaw(
                'ec_product_variation_items.id IN
                (
                    SELECT ec_product_variation_items.id
                    FROM ec_product_variation_items
                    JOIN ec_product_variations ON ec_product_variations.id = ec_product_variation_items.variation_id
                    WHERE ec_product_variations.configurable_product_id = ' . $configurableProductId . '
                    AND ec_product_variation_items.attribute_id NOT IN (' . implode(',', $attributes) . ')
                )
            '
            )
            ->where('ec_product_variations.configurable_product_id', $configurableProductId)
            ->distinct()
            ->pluck('ec_product_variation_items.id')
            ->all();

        return ProductVariationItem::query()->whereIn('id', $items)->delete();
    }

    public static function getParentOfVariation(int|string $variationId, array $with = []): ?Product
    {
        $variation = self::query()
            ->where('product_id', $variationId);

        $variation = $variation->first();

        if (empty($variation)) {
            $product = Product::query()->with($with)->find($variationId);
        } else {
            $product = Product::query()->with($with)->find($variation->configurable_product_id);
        }

        /**
         * @var Product $product
         */
        return $product;
    }

    public static function getAttributeIdsOfChildrenProduct(int|string $productId): array
    {
        return self::query()
            ->join(
                'ec_product_variation_items',
                'ec_product_variation_items.variation_id',
                '=',
                'ec_product_variations.id'
            )
            ->distinct()
            ->select('ec_product_variation_items.attribute_id')
            ->where('ec_product_variations.product_id', $productId)
            ->get()
            ->pluck('attribute_id')
            ->all();
    }

    protected function image(): Attribute
    {
        return Attribute::get(fn () => $this->product->image ?: $this->configurableProduct->image);
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn () => $this->product->name);
    }
}
