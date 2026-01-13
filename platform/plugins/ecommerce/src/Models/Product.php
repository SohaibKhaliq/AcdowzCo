<?php

namespace Botble\Ecommerce\Models;

use Botble\ACL\Models\User;
use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\DiscountTargetEnum;
use Botble\Ecommerce\Enums\DiscountTypeEnum;
use Botble\Ecommerce\Enums\ProductLicenseCodeStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Services\ProductCacheService;
use Botble\Ecommerce\Services\Products\UpdateDefaultProductService;
use Botble\Faq\Models\Faq;
use Botble\Media\Facades\RvMedia;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Supports\Vimeo;
use Botble\Theme\Supports\Youtube;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @method notOutOfStock()
 */
class Product extends BaseModel
{
    use Concerns\ProductPrices;

    protected $table = 'ec_products';

    protected $fillable = [
        'name',
        'description',
        'content',
        'image', // Featured image
        'images',
        'video_media',
        'sku',
        'order',
        'quantity',
        'allow_checkout_when_out_of_stock',
        'with_storehouse_management',
        'is_featured',
        'brand_id',
        'is_variation',
        'sale_type',
        'price',
        'sale_price',
        'start_date',
        'end_date',
        'length',
        'wide',
        'height',
        'weight',
        'tax_id',
        'views',
        'stock_status',
        'barcode',
        'cost_per_item',
        'generate_license_code',
        'license_code_type',
        'minimum_order_quantity',
        'maximum_order_quantity',
        'notify_attachment_updated',
        'specification_table_id',
        'slug',
    ];

    protected $appends = [
        'original_price',
        'front_sale_price',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'stock_status' => StockStatusEnum::class,
        'product_type' => ProductTypeEnum::class,
        'price' => 'float',
        'sale_price' => 'float',
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'sale_type' => 'int',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'minimum_order_quantity' => 'int',
        'maximum_order_quantity' => 'int',
        'is_featured' => 'bool',
        'allow_checkout_when_out_of_stock' => 'bool',
        'with_storehouse_management' => 'bool',
        'generate_license_code' => 'bool',
        'notify_attachment_updated' => 'bool',
        'video_media' => 'json',
        'length' => 'float',
        'wide' => 'float',
        'height' => 'float',
        'weight' => 'float',
        'views' => 'int',
        'quantity' => 'int',
        'order' => 'int',
        'cost_per_item' => 'float',
        'is_variation' => 'bool',
        'variations_count' => 'int',
        'reviews_count' => 'int',
        'reviews_avg' => 'float',
    ];

    protected function url(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->slug && $this->slugable) {
                $this->slug = $this->slugable->key;
            }

            if (! $this->slug) {
                return BaseHelper::getHomepageUrl();
            }

            $prefix = SlugHelper::getPrefix(Product::class);

            return apply_filters(
                'slug_filter_url',
                url(ltrim($prefix . '/' . $this->slug, '/')) . SlugHelper::getPublicSingleEndingURL()
            );
        });
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            $product->created_by_id = Auth::check() ? Auth::id() : 0;
            $product->created_by_type = User::class;
        });

        static::deleting(function (Product $product): void {
            app(ProductCacheService::class)->clearProductCache($product);
        });

        static::deleted(function (Product $product): void {
            $product->variations()->each(fn (ProductVariation $item) => $item->delete());
            $product->variationInfo()->delete();
            $product->categories()->detach();
            $product->productAttributeSets()->detach();
            $product->productCollections()->detach();
            $product->discounts()->detach();
            $product->crossSales()->detach();
            $product->upSales()->detach();
            $product->groupedProduct()->detach();
            $product->taxes()->detach();
            $product->views()->delete();
            $product->reviews()->delete();
            $product->flashSales()->detach();
            $product->productFiles()->delete();
            $product->productLabels()->detach();
            $product->tags()->detach();
            $product->specificationAttributes()->detach();
            $product->licenseCodes()->delete();
        });

        static::saved(function (Product $product): void {
            app(ProductCacheService::class)->clearProductCache($product);
        });

        static::updated(function (Product $product): void {
            if ($product->is_variation && $product->original_product->defaultVariation->product_id == $product->getKey()) {
                app(UpdateDefaultProductService::class)->execute($product);
            }

            // Trigger quantity updated event if quantity, stock status, or storehouse management changed
            $quantityRelatedFields = ['quantity', 'stock_status', 'with_storehouse_management', 'allow_checkout_when_out_of_stock'];
            if ($product->wasChanged($quantityRelatedFields)) {
                ProductQuantityUpdatedEvent::dispatch($product);
            }

            if (! $product->is_variation && $product->variations()->exists()) {
                Product::query()
                    ->whereIn('id', $product->variations()->pluck('product_id')->all())
                    ->where('is_variation', 1)
                    ->update([
                        'name' => $product->name,
                        'minimum_order_quantity' => $product->minimum_order_quantity,
                        'maximum_order_quantity' => $product->maximum_order_quantity,
                    ]);
            }

            EcommerceHelper::clearProductMaxPriceCache();
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'ec_product_category_product',
            'product_id',
            'category_id'
        );
    }

    public function productAttributeSets(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttributeSet::class,
            'ec_product_with_attribute_set',
            'product_id',
            'attribute_set_id'
        );
    }

    public function productCollections(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCollection::class,
            'ec_product_collection_products',
            'product_id',
            'product_collection_id'
        );
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'ec_discount_products', 'product_id', 'discount_id');
    }

    public function crossSales(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Product::class,
                'ec_product_cross_sale_relations',
                'from_product_id',
                'to_product_id'
            )
            ->withPivot(['price', 'price_type', 'apply_to_all_variations', 'is_variant']);
    }

    public function upSales(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ec_product_up_sale_relations', 'from_product_id', 'to_product_id');
    }

    public function groupedProduct(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ec_grouped_products', 'parent_product_id', 'product_id');
    }

    public function productLabels(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductLabel::class,
            'ec_product_label_products',
            'product_id',
            'product_label_id'
        );
    }

    public function taxes(): BelongsToMany
    {
        return $this->original_product->belongsToMany(Tax::class, 'ec_tax_products')->with(['rules']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductTag::class,
            'ec_product_tag_product',
            'product_id',
            'tag_id'
        );
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class)->withDefault();
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'ec_product_related_relations', 'from_product_id', 'to_product_id')
            ->where('is_variation', 0);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'configurable_product_id');
    }

    public function parentProduct(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ec_product_variations', 'product_id', 'configurable_product_id');
    }

    public function variationAttributeSwatchesForProductList(): HasMany
    {
        return $this
            ->hasMany(ProductVariation::class, 'configurable_product_id')
            ->join(
                'ec_product_variation_items',
                'ec_product_variation_items.variation_id',
                '=',
                'ec_product_variations.id'
            )
            ->join('ec_product_attributes', 'ec_product_attributes.id', '=', 'ec_product_variation_items.attribute_id')
            ->join(
                'ec_product_attribute_sets',
                'ec_product_attribute_sets.id',
                '=',
                'ec_product_attributes.attribute_set_id'
            )
            ->where('ec_product_attribute_sets.status', BaseStatusEnum::PUBLISHED)
            ->where('ec_product_attribute_sets.is_use_in_product_listing', 1)
            ->select([
                'ec_product_attributes.*',
                'ec_product_variations.*',
                'ec_product_variation_items.*',
                'ec_product_attribute_sets.*',
                'ec_product_attributes.title as attribute_title',
            ]);
    }

    public function variationInfo(): HasOne
    {
        return $this->hasOne(ProductVariation::class, 'product_id')->withDefault();
    }

    public function defaultVariation(): HasOne
    {
        return $this
            ->hasOne(ProductVariation::class, 'configurable_product_id')
            ->where('ec_product_variations.is_default', 1)
            ->withDefault();
    }

    public function groupedItems(): HasMany
    {
        return $this->hasMany(GroupedProduct::class, 'parent_product_id');
    }

    public function specificationTable(): BelongsTo
    {
        return $this->belongsTo(SpecificationTable::class);
    }

    public function specificationAttributes(): BelongsToMany
    {
        return $this
            ->belongsToMany(SpecificationAttribute::class, 'ec_product_specification_attribute', 'product_id', 'attribute_id')
            ->withPivot('value', 'hidden', 'order');
    }

    public function getVisibleSpecificationAttributes()
    {
        return $this->specificationAttributes
            ->where('pivot.hidden', false)
            ->sortBy('pivot.order');
    }

    public function getSpecificationAttributePivot(SpecificationAttribute $attribute)
    {
        return $this->specificationAttributes->where('id', $attribute->id)->first();
    }

    protected function crossSaleProducts(): Attribute
    {
        return Attribute::get(function () {
            $this->loadMissing('crossSales');

            return $this->crossSales->filter(
                fn (Product $product) => ! $product->pivot->is_variant
            );
        });
    }

    protected function images(): Attribute
    {
        return Attribute::make(
            get: function (array|string|null $value): array {
                try {
                    if ($value === '[null]') {
                        return [];
                    }

                    $images = $value;

                    if (! is_array($images)) {
                        $images = json_decode((string) $value, true);
                    }

                    if (is_array($images)) {
                        $images = array_filter($images);
                    }

                    return $images ?: [];
                } catch (Exception) {
                    return [];
                }
            }
        );
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                $firstImage = Arr::first($this->images) ?: null;

                if ($this->is_variation) {
                    return $firstImage;
                }

                return $value ?: $firstImage;
            }
        );
    }

    protected function stockStatusLabel(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if ($this->with_storehouse_management) {
                    return $this->isOutOfStock() ? StockStatusEnum::OUT_OF_STOCK()->label() : StockStatusEnum::IN_STOCK()
                        ->label();
                }

                return $this->stock_status->label();
            }
        );
    }

    protected function stockStatusHtml(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if ($this->with_storehouse_management) {
                    return $this->isOutOfStock() ? StockStatusEnum::OUT_OF_STOCK()->toHtml() : StockStatusEnum::IN_STOCK()
                        ->toHtml();
                }

                return $this->stock_status->toHtml();
            }
        );
    }

    protected function originalProduct(): Attribute
    {
        return Attribute::make(
            get: function (): int|null|self {
                if (! $this->is_variation) {
                    return $this;
                }

                return $this->variationInfo->id ? $this->variationInfo->configurableProduct : $this;
            }
        );
    }

    protected function hasVariations(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->variations_count > 1;
            }
        );
    }

    protected function hasVariation(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->variations_count > 0;
            }
        );
    }

    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->reviews_avg
        );
    }

    public function isOutOfStock(): bool
    {
        if (! $this->with_storehouse_management) {
            return $this->stock_status == StockStatusEnum::OUT_OF_STOCK;
        }

        return $this->quantity <= 0 && ! $this->allow_checkout_when_out_of_stock;
    }

    public function canAddToCart(int $quantity): bool
    {
        if ($this->with_storehouse_management && $this->allow_checkout_when_out_of_stock) {
            return true;
        }

        if ($this->max_cart_quantity < $quantity) {
            return false;
        }

        if (! $this->with_storehouse_management) {
            return true;
        }

        return ($this->quantity - $quantity) >= 0;
    }

    public function promotions(): BelongsToMany
    {
        return $this
            ->belongsToMany(Discount::class, 'ec_discount_products', 'product_id')
            ->where('type', DiscountTypeEnum::PROMOTION)
            ->where('start_date', '<=', Carbon::now())
            ->whereIn('target', [DiscountTargetEnum::SPECIFIC_PRODUCT, DiscountTargetEnum::PRODUCT_VARIANT])
            ->where(function ($query) {
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('product_quantity', 1);
    }

    public function tax(): BelongsTo
    {
        if (! $this->original_product->tax_id && $defaultTaxRate = get_ecommerce_setting('default_tax_rate')) {
            $this->original_product->tax_id = $defaultTaxRate;
        }

        return $this->original_product->belongsTo(Tax::class, 'tax_id')->withDefault();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id')->wherePublished();
    }

    public function views(): HasMany
    {
        return $this->hasMany(ProductView::class, 'product_id');
    }

    public function flashSales(): BelongsToMany
    {
        return $this->original_product
            ->belongsToMany(FlashSale::class, 'ec_flash_sale_products', 'product_id', 'flash_sale_id')
            ->withPivot(['price', 'quantity', 'sold']);
    }

    public function latestFlashSales(): BelongsToMany
    {
        // @phpstan-ignore-next-line
        return $this
            ->flashSales()
            ->wherePublished()
            ->notExpired()
            ->wherePivot('quantity', '>', DB::raw('sold'))
            ->latest();
    }

    protected function totalTaxesPercentage(): Attribute
    {
        return Attribute::get(function () {
            $taxes = $this->taxes
                ->where(fn ($item) => ! $item->rules || $item->rules->isEmpty())
                ->where('status', BaseStatusEnum::PUBLISHED);

            if ($taxes->isEmpty() && $defaultTaxRate = get_ecommerce_setting('default_tax_rate')) {
                return Tax::query()->where('id', $defaultTaxRate)->value('percentage') ?: 0;
            }

            return $taxes->sum('percentage');
        });
    }

    public function variationProductAttributes(): HasMany
    {
        return $this
            ->hasMany(ProductVariation::class, 'product_id')
            ->join(
                'ec_product_variation_items',
                'ec_product_variation_items.variation_id',
                '=',
                'ec_product_variations.id'
            )
            ->join('ec_product_attributes', 'ec_product_attributes.id', '=', 'ec_product_variation_items.attribute_id')
            ->join(
                'ec_product_attribute_sets',
                'ec_product_attribute_sets.id',
                '=',
                'ec_product_attributes.attribute_set_id'
            )
            ->distinct()
            ->select([
                'ec_product_variations.product_id',
                'ec_product_variations.configurable_product_id',
                'ec_product_attributes.*',
                'ec_product_attribute_sets.title as attribute_set_title',
                'ec_product_attribute_sets.slug as attribute_set_slug',
                'ec_product_attribute_sets.order as attribute_set_order',
            ])
            ->oldest('attribute_set_order');
    }

    protected function variationAttributes(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->variationProductAttributes->count()) {
                return '';
            }

            $attributes = $this->variationProductAttributes->pluck('title', 'attribute_set_title')->toArray();

            return '(' . mapped_implode(', ', $attributes, ': ') . ')';
        });
    }

    public function createdBy(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    protected function faqItems(): Attribute
    {
        return Attribute::get(function () {
            $this->loadMissing('metadata');

            $faqs = (array) $this->getMetaData('faq_schema_config', true);

            if (is_plugin_active('faq')) {
                $selectedExistingFaqs = $this->getMetaData('faq_ids', true);

                if ($selectedExistingFaqs && is_array($selectedExistingFaqs)) {
                    $selectedExistingFaqs = array_filter($selectedExistingFaqs);

                    if ($selectedExistingFaqs) {
                        $selectedFaqs = Faq::query()
                            ->wherePublished()
                            ->whereIn('id', $selectedExistingFaqs)
                            ->select(['id', 'question', 'answer'])
                            ->get();

                        foreach ($selectedFaqs as $selectedFaq) {
                            $faqs[] = [
                                [
                                    'key' => 'question',
                                    'value' => $selectedFaq->question,
                                ],
                                [
                                    'key' => 'answer',
                                    'value' => $selectedFaq->answer,
                                ],
                            ];
                        }
                    }
                }
            }

            $faqs = array_filter($faqs);

            if (empty($faqs)) {
                return [];
            }

            foreach ($faqs as $key => $item) {
                if (! is_array($item) || ! isset($item[0], $item[1]) ||
                    ! isset($item[0]['value'], $item[1]['value']) ||
                    (! $item[0]['value'] && ! $item[1]['value'])) {
                    Arr::forget($faqs, $key);
                }
            }

            return $faqs;
        })->shouldCache();
    }

    protected function reviewImages(): Attribute
    {
        return Attribute::get(fn () => $this->reviews->sortByDesc('created_at')->reduce(function ($carry, $item) {
            return array_merge($carry, (array) $item->images);
        }, []));
    }

    public function isTypePhysical(): bool
    {
        return ! isset($this->attributes['product_type']) || $this->attributes['product_type'] == ProductTypeEnum::PHYSICAL;
    }

    public function isTypeDigital(): bool
    {
        if (EcommerceHelper::isDisabledPhysicalProduct()) {
            return true;
        }

        return isset($this->attributes['product_type']) && $this->attributes['product_type'] == ProductTypeEnum::DIGITAL;
    }

    public function productFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class, 'product_id');
    }

    public function licenseCodes(): HasMany
    {
        return $this->hasMany(ProductLicenseCode::class, 'product_id');
    }

    public function availableLicenseCodes(): HasMany
    {
        return $this->hasMany(ProductLicenseCode::class, 'product_id')->where('status', ProductLicenseCodeStatusEnum::AVAILABLE);
    }

    public function usedLicenseCodes(): HasMany
    {
        return $this->hasMany(ProductLicenseCode::class, 'product_id')->where('status', ProductLicenseCodeStatusEnum::USED);
    }

    protected function productFileExternalCount(): Attribute
    {
        return Attribute::get(fn () => $this->productFiles->filter(fn (ProductFile $file) => $file->is_external_link)->count());
    }

    protected function productFileInternalCount(): Attribute
    {
        return Attribute::get(fn () => $this->productFiles->filter(fn (ProductFile $file) => ! $file->is_external_link)->count());
    }

    public function hasFiles(): bool
    {
        return $this->productFiles()->count() > 0;
    }

    public function scopeForCart(Builder $query): Builder
    {
        return $query->with([
            'variations',
            'defaultVariation.product',
            'variationInfo.configurableProduct',
        ]);
    }

    public function scopeNotOutOfStock(Builder $query): Builder
    {
        if (EcommerceHelper::showOutOfStockProducts() || is_in_admin()) {
            return $query;
        }

        return $query
            ->where(function ($query): void {
                $query
                    ->where(function ($subQuery): void {
                        $subQuery
                            ->where('with_storehouse_management', 0)
                            ->where('stock_status', '!=', StockStatusEnum::OUT_OF_STOCK);
                    })
                    ->orWhere(function ($subQuery): void {
                        $subQuery
                            ->where('with_storehouse_management', 1)
                            ->where('quantity', '>', 0);
                    })
                    ->orWhere(function ($subQuery): void {
                        $subQuery
                            ->where('with_storehouse_management', 1)
                            ->where('allow_checkout_when_out_of_stock', 1);
                    });
            });
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->oldest('order');
    }

    public function generateSku(): float|string|null
    {
        if (
            ! get_ecommerce_setting('auto_generate_product_sku', true) ||
            ! $setting = get_ecommerce_setting('product_sku_format', null)
        ) {
            return null;
        }

        if (! Str::contains($setting, ['[%s]', '[%d]', '[%S]', '[%D]', '%s', '%d'])) {
            return $setting . (mt_rand(10000, 99999) + time());
        }

        $sku = str_replace(
            ['[%s]', '[%S]'],
            strtoupper(Str::random(5)),
            $setting
        );

        $sku = str_replace(
            ['[%d]', '[%D]'],
            (string) mt_rand(10000, 99999),
            $sku
        );

        foreach (explode('%s', $sku) as $ignored) {
            $sku = preg_replace('/%s/i', strtoupper(Str::random(1)), $sku, 1);
        }

        foreach (explode('%d', $sku) as $ignored) {
            $sku = preg_replace('/%d/i', (string) mt_rand(0, 9), $sku, 1);
        }

        if ($this->query()->where('sku', $sku)->exists()) {
            return $sku . (mt_rand(10000, 99999) + time());
        }

        return $sku;
    }

    public static function getGroupedVariationQuery(): QueryBuilder
    {
        $variationAttributesSubquery = DB::table('ec_product_variations as pv')
            ->select([
                'pv.product_id',
                DB::raw("GROUP_CONCAT(CONCAT(pas.title, ': ', pa.title) ORDER BY pas.order, pa.order SEPARATOR ', ') as variation_attributes"),
            ])
            ->leftJoin('ec_product_variation_items as pvi', 'pvi.variation_id', '=', 'pv.id')
            ->leftJoin('ec_product_attributes as pa', 'pa.id', '=', 'pvi.attribute_id')
            ->leftJoin('ec_product_attribute_sets as pas', 'pas.id', '=', 'pa.attribute_set_id')
            ->groupBy('pv.product_id');

        $variationsCountSubquery = DB::table('ec_product_variations')
            ->select([
                'configurable_product_id',
                DB::raw('COUNT(*) as variations_count'),
            ])
            ->groupBy('configurable_product_id');

        return DB::table('ec_products')
            ->select([
                'ec_products.id',
                'ec_products.name',
                'ec_products.image',
                'ec_products.images',
                'ec_products.sku',
                'ec_products.is_variation',
                'pv.configurable_product_id as parent_product_id',
                'va.variation_attributes',
                'vc.variations_count',
            ])
            ->leftJoin('ec_product_variations as pv', function (JoinClause $join): void {
                $join->on('ec_products.id', '=', 'pv.product_id')
                     ->where('ec_products.is_variation', '=', 1);
            })
            ->leftJoinSub($variationAttributesSubquery, 'va', function (JoinClause $join): void {
                $join->on('ec_products.id', '=', 'va.product_id');
            })
            ->leftJoinSub($variationsCountSubquery, 'vc', function (JoinClause $join): void {
                $join->on('ec_products.id', '=', 'vc.configurable_product_id');
            })
            ->orderBy('ec_products.name')
            ->orderBy('parent_product_id');
    }

    public static function getDigitalProductFilesDirectory(): string
    {
        return 'ecommerce/digital-product-files';
    }

    public function getIdForCart(): int|string|null
    {
        return ($this->is_variation || ! $this->defaultVariation->product_id) ? $this->id : $this->defaultVariation->product_id;
    }

    protected function video(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->video_media || ! is_array($this->video_media) || ! count($this->video_media)) {
                return [];
            }

            return collect($this->video_media)
                ->map(function (array $item) {
                    $url = Arr::get($item, '0.value');

                    if ($url) {
                        $url = RvMedia::url($url);
                    } else {
                        $url = Arr::get($item, '1.value');
                    }

                    $thumbnail = Arr::get($item, '2.value');

                    $data = [
                        'url' => $url,
                        'thumbnail' => $thumbnail ? RvMedia::getImageUrl($thumbnail) : null,
                    ];

                    if (Youtube::isYoutubeURL($url)) {
                        $data['provider'] = 'youtube';
                        $data['url'] = Youtube::getYoutubeVideoEmbedURL($url) . '?enablejsapi=1&iv_load_policy=3&fs=0&rel=0&loop=1&start=1';
                        if (! $data['thumbnail']) {
                            $data['thumbnail'] = Youtube::getThumbnail($url);
                        }
                    } elseif (Vimeo::isVimeoURL($url)) {
                        $videoId = Vimeo::getVimeoID($url);
                        if ($videoId) {
                            $data['provider'] = 'vimeo';
                            $data['url'] = 'https://player.vimeo.com/video/' . $videoId;
                            if (! $data['thumbnail']) {
                                $data['thumbnail'] = 'https://vumbnail.com/' . $videoId . '.jpg';
                            }
                        }
                    } elseif (preg_match(
                        '/^.*https:\/\/(?:m|www|vm)?\.?tiktok\.com\/((?:.*\b(?:(?:usr|v|embed|user|video)\/|\?shareId=|\&item_id=)(\d+))|\w+)/',
                        $url
                    )) {
                        $data['provider'] = 'tiktok';
                        $data['video_id'] = Str::afterLast($url, 'video/');
                    } elseif (preg_match('/^.*https:\/\/twitter\.com\/(?:#!\/)?(\w+)\/status(es)?\/(\d+)/', $url)) {
                        $data['provider'] = 'twitter';
                    } elseif (in_array(Str::lower(File::extension($url)), ['mp4', 'webm', 'ogg'])) {
                        $data['provider'] = 'video';
                    } else {
                        $data['provider'] = 'iframe';
                    }

                    if (! $data['thumbnail']) {
                        $data['thumbnail'] = RvMedia::getDefaultImage();
                    }

                    return $data;
                })
                ->all();
        })->shouldCache();
    }

    protected function minCartQuantity(): Attribute
    {
        return Attribute::get(function () {
            return $this->minimum_order_quantity ?: 1;
        });
    }

    protected function maxCartQuantity(): Attribute
    {
        return Attribute::get(function () {
            if ($this->maximum_order_quantity) {
                return $this->maximum_order_quantity;
            }

            return $this->with_storehouse_management ? $this->quantity : 1000;
        });
    }

    protected function taxDescription(): Attribute
    {
        return Attribute::get(function () {
            if (! EcommerceHelper::isTaxEnabled() || ! get_ecommerce_setting('display_tax_description', false)) {
                return null;
            }

            $taxes = $this->taxes->isNotEmpty()
                ? $this->taxes
                : collect([(object) [
                    'title' => get_ecommerce_setting('default_tax_rate') ? Tax::query()->find(get_ecommerce_setting('default_tax_rate'))->title : '',
                    'percentage' => get_ecommerce_setting('default_tax_rate') ? Tax::query()->find(get_ecommerce_setting('default_tax_rate'))->percentage : 0,
                ]]);

            $taxes = $taxes->filter(fn ($tax) => $tax->percentage > 0);

            if ($taxes->isEmpty()) {
                return null;
            }

            $taxNames = $taxes->map(fn ($tax) => $tax->title . ' ' . $tax->percentage . '%')->implode(' + ');

            if (EcommerceHelper::isDisplayProductIncludingTaxes()) {
                return '(' . __('Including :tax', ['tax' => $taxNames]) . ')';
            }

            return '(' . __('Excluding :tax', ['tax' => $taxNames]) . ')';
        });
    }

    public function updateReviewsCache(): void
    {
        $stats = $this->reviews()
            ->selectRaw('COUNT(*) as count, AVG(star) as avg')
            ->first();

        $count = $stats->count ?? 0;
        $avg = round($stats->avg ?? 0, 2);

        DB::table('ec_products')
            ->where('id', $this->id)
            ->update([
                'reviews_count' => $count,
                'reviews_avg' => $avg,
            ]);

        $this->setAttribute('reviews_count', $count);
        $this->setAttribute('reviews_avg', $avg);
    }
}
