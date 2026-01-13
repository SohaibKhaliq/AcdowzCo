<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Slug\Events\UpdatedSlugEvent;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncProductSlug
{
    public function handleUpdatedSlug(UpdatedSlugEvent $event): void
    {
        if ($event->slug && $event->data) {
            if ($event->data instanceof ProductCategory) {
                $this->syncCategorySlug($event->data->id, $event->slug);
            } elseif ($event->data instanceof Product) {
                $this->syncProductSlug($event->data->id, $event->slug);
            }
        }
    }

    public function handleCreatedContent(CreatedContentEvent $event): void
    {
        $this->handleContentEvent($event->data);
    }

    public function handleUpdatedContent(UpdatedContentEvent $event): void
    {
        $this->handleContentEvent($event->data);
    }

    protected function handleContentEvent($model): void
    {
        if ($model instanceof ProductCategory) {
            $slug = Slug::query()
                ->where('reference_type', ProductCategory::class)
                ->where('reference_id', $model->id)
                ->first();

            if ($slug) {
                $this->syncCategorySlug($model->id, $slug);
            }
        } elseif ($model instanceof Product) {
            $slug = Slug::query()
                ->where('reference_type', Product::class)
                ->where('reference_id', $model->id)
                ->first();

            if ($slug) {
                $this->syncProductSlug($model->id, $slug);
            }
        }
    }

    protected function syncCategorySlug(int $categoryId, Slug $slug): void
    {
        ProductCategory::query()
            ->where('id', $categoryId)
            ->update(['slug' => $slug->key]);

        if (Schema::hasTable('slugs_translations') && Schema::hasTable('ec_product_categories_translations')) {
            $translatedSlugs = DB::table('slugs_translations')
                ->where('slugs_id', $slug->id)
                ->get();

            foreach ($translatedSlugs as $translatedSlug) {
                DB::table('ec_product_categories_translations')
                    ->where('ec_product_categories_id', $categoryId)
                    ->where('lang_code', $translatedSlug->lang_code)
                    ->update(['slug' => $translatedSlug->key]);
            }
        }
    }

    protected function syncProductSlug(int $productId, Slug $slug): void
    {
        Product::query()
            ->where('id', $productId)
            ->update(['slug' => $slug->key]);

        if (Schema::hasTable('slugs_translations') && Schema::hasTable('ec_products_translations')) {
            $translatedSlugs = DB::table('slugs_translations')
                ->where('slugs_id', $slug->id)
                ->get();

            foreach ($translatedSlugs as $translatedSlug) {
                DB::table('ec_products_translations')
                    ->where('ec_products_id', $productId)
                    ->where('lang_code', $translatedSlug->lang_code)
                    ->update(['slug' => $translatedSlug->key]);
            }
        }
    }
}
