<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Contracts\HasTreeCategory as HasTreeCategoryContract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\HasTreeCategory;
use Botble\Ecommerce\Tables\ProductTable;
use Botble\Media\Facades\RvMedia;
use Botble\Slug\Facades\SlugHelper;
use Botble\Support\Services\Cache\Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductCategory extends BaseModel implements HasTreeCategoryContract
{
    use HasTreeCategory;

    protected $table = 'ec_product_categories';

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'order',
        'status',
        'image',
        'is_featured',
        'icon',
        'icon_image',
        'slug',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'is_featured' => 'bool',
        'order' => 'int',
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

            $prefix = SlugHelper::getPrefix(ProductCategory::class);

            return apply_filters(
                'slug_filter_url',
                url(ltrim($prefix . '/' . $this->slug, '/')) . SlugHelper::getPublicSingleEndingURL()
            );
        });
    }

    protected static function booted(): void
    {
        static::deleted(function (ProductCategory $category): void {
            $category->products()->detach();

            $category->children()->each(fn (ProductCategory $child) => $child->delete());

            $category->brands()->detach();
            $category->productAttributeSets()->detach();
        });

        static::saved(function (ProductCategory $category): void {
            Cache::make(static::class)->flush();

            $category->clearParentCache();
        });

        static::deleted(function (ProductCategory $category): void {
            Cache::make(static::class)->flush();

            $category->clearParentCache();
        });
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Product::class,
                'ec_product_category_product',
                'category_id',
                'product_id'
            )
            ->where('is_variation', 0);
    }

    public function parent(): BelongsTo
    {
        return $this
            ->belongsTo(ProductCategory::class, 'parent_id')
            ->whereNot('parent_id', $this->getKey())
            ->withDefault();
    }

    public function children(): HasMany
    {
        return $this
            ->hasMany(ProductCategory::class, 'parent_id')
            ->whereNot('id', $this->getKey());
    }

    public function activeChildren(): HasMany
    {
        return $this
            ->children()
            ->wherePublished()
            ->orderBy('order')
            ->with(['slugable', 'activeChildren']);
    }

    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'reference', 'ec_product_categorizables', 'category_id');
    }

    public function productAttributeSets(): MorphToMany
    {
        return $this->morphedByMany(ProductAttributeSet::class, 'reference', 'ec_product_categorizables', 'category_id');
    }

    protected function parents(): Attribute
    {
        return Attribute::get(function (): Collection {
            $parents = collect();

            if (! $this->parent_id) {
                return $parents;
            }

            $cacheKey = 'product_category_parents_' . $this->id;

            return cache()->remember($cacheKey, 3600, function () {
                return $this->loadParentsRecursively();
            });
        });
    }

    protected function loadParentsRecursively(): Collection
    {
        $parents = collect();
        $parentIds = [];
        $currentParentId = $this->parent_id;

        $maxDepth = 10;
        while ($currentParentId && $maxDepth > 0) {
            if (in_array($currentParentId, $parentIds)) {
                break;
            }
            $parentIds[] = $currentParentId;
            $maxDepth--;

            $nextParent = DB::table('ec_product_categories')
                ->where('id', $currentParentId)
                ->select('parent_id')
                ->first();

            $currentParentId = $nextParent?->parent_id;
        }

        if (! empty($parentIds)) {
            $allParents = ProductCategory::query()
                ->whereIn('id', $parentIds)
                ->get()
                ->keyBy('id');

            foreach ($parentIds as $parentId) {
                if ($allParents->has($parentId)) {
                    $parents->push($allParents->get($parentId));
                }
            }
        }

        return $parents;
    }

    public function clearParentCache(): void
    {
        cache()->forget('product_category_parents_' . $this->id);

        $childIds = $this->children()->pluck('id')->all();
        foreach ($childIds as $childId) {
            cache()->forget('product_category_parents_' . $childId);
        }
    }

    public static function withAllParents($categories): void
    {
        if ($categories->isEmpty()) {
            return;
        }

        $allParentIds = [];
        foreach ($categories as $category) {
            if ($category->parent_id) {
                $allParentIds[] = $category->parent_id;
            }
        }

        if (empty($allParentIds)) {
            return;
        }

        $loadedIds = [];
        $maxDepth = 10;

        while (! empty($allParentIds) && $maxDepth > 0) {
            $allParentIds = array_unique(array_diff($allParentIds, $loadedIds));

            if (empty($allParentIds)) {
                break;
            }

            $parents = static::query()
                ->whereIn('id', $allParentIds)
                ->get();

            foreach ($parents as $parent) {
                cache()->put('product_category_' . $parent->id, $parent, 3600);
                $loadedIds[] = $parent->id;
            }

            $allParentIds = $parents->pluck('parent_id')->filter()->toArray();
            $maxDepth--;
        }
    }

    protected function badgeWithCount(): Attribute
    {
        return Attribute::get(function (): HtmlString {
            $productsCount = $this->count_all_products;

            $link = route('products.index', [
                'filter_table_id' => strtolower(Str::slug(Str::snake(ProductTable::class))),
                'class' => Product::class,
                'filter_columns' => ['category'],
                'filter_operators' => ['='],
                'filter_values' => [$this->getKey()],
            ]);

            return Html::link($link, sprintf('(%d)', $productsCount), [
                'data-bs-toggle' => 'tooltip',
                'data-bs-original-title' => trans('plugins/ecommerce::product-categories.total_products', ['total' => $productsCount]),
                'target' => '_blank',
            ]);
        });
    }

    protected function countAllProducts(): Attribute
    {
        return Attribute::get(function (): int {
            $cache = Cache::make(static::class);
            $cacheKey = 'count_all_products_' . $this->getKey() . app()->getLocale();

            if ($cache->has($cacheKey)) {
                return $cache->get($cacheKey);
            }

            $categoryIds = static::getChildrenIds($this->activeChildren);

            $categoryIds[] = $this->getKey();

            $count = DB::table('ec_product_category_product')
                ->join('ec_products', 'ec_product_category_product.product_id', '=', 'ec_products.id')
                ->whereIn('category_id', $categoryIds)
                ->where('ec_products.status', BaseStatusEnum::PUBLISHED)
                ->where('ec_products.is_variation', 0)
                ->distinct('product_id')
                ->count();

            $cache->put($cacheKey, $count, Carbon::now()->addHours(2));

            return $count;
        });
    }

    protected function iconHtml(): Attribute
    {
        return Attribute::get(function (): ?string {
            if ($this->icon_image) {
                return RvMedia::image($this->icon_image, $this->name);
            }

            if ($this->icon) {
                return BaseHelper::renderIcon($this->icon);
            }

            return null;
        });
    }

    public static function getChildrenIds(EloquentCollection $children, $categoryIds = []): array
    {
        if ($children->isEmpty()) {
            return $categoryIds;
        }

        foreach ($children as $item) {
            $categoryIds[] = $item->id;
            if ($item->activeChildren->isNotEmpty()) {
                $categoryIds = static::getChildrenIds($item->activeChildren, $categoryIds);
            }
        }

        return $categoryIds;
    }
}
