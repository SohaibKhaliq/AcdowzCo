<?php

use Botble\Ecommerce\Models\ProductCategory;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        $categories = ProductCategory::query()
            ->select(['id'])
            ->get();

        foreach ($categories as $category) {
            $slug = Slug::query()
                ->where('reference_type', ProductCategory::class)
                ->where('reference_id', $category->id)
                ->first();

            if ($slug) {
                ProductCategory::query()
                    ->where('id', $category->id)
                    ->update(['slug' => $slug->key]);
            }
        }

        if (Schema::hasTable('ec_product_categories_translations')) {
            if (Schema::hasTable('slugs_translations')) {
                $translatedSlugs = DB::table('slugs_translations')
                    ->join('slugs', 'slugs.id', '=', 'slugs_translations.slugs_id')
                    ->where('slugs.reference_type', ProductCategory::class)
                    ->select([
                        'slugs.reference_id',
                        'slugs_translations.lang_code',
                        'slugs_translations.key',
                    ])
                    ->get();

                foreach ($translatedSlugs as $translatedSlug) {
                    DB::table('ec_product_categories_translations')
                        ->where('ec_product_categories_id', $translatedSlug->reference_id)
                        ->where('lang_code', $translatedSlug->lang_code)
                        ->update(['slug' => $translatedSlug->key]);
                }
            }
        }
    }

    public function down(): void
    {
        ProductCategory::query()->update(['slug' => null]);

        if (Schema::hasTable('ec_product_categories_translations')) {
            DB::table('ec_product_categories_translations')->update(['slug' => null]);
        }
    }
};
