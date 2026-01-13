<?php

use Botble\Ecommerce\Models\Product;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        $products = Product::query()
            ->select(['id'])
            ->get();

        foreach ($products as $product) {
            $slug = Slug::query()
                ->where('reference_type', Product::class)
                ->where('reference_id', $product->id)
                ->first();

            if ($slug) {
                Product::query()
                    ->where('id', $product->id)
                    ->update(['slug' => $slug->key]);
            }
        }

        if (Schema::hasTable('ec_products_translations')) {
            if (Schema::hasTable('slugs_translations')) {
                $translatedSlugs = DB::table('slugs_translations')
                    ->join('slugs', 'slugs.id', '=', 'slugs_translations.slugs_id')
                    ->where('slugs.reference_type', Product::class)
                    ->select([
                        'slugs.reference_id',
                        'slugs_translations.lang_code',
                        'slugs_translations.key',
                    ])
                    ->get();

                foreach ($translatedSlugs as $translatedSlug) {
                    DB::table('ec_products_translations')
                        ->where('ec_products_id', $translatedSlug->reference_id)
                        ->where('lang_code', $translatedSlug->lang_code)
                        ->update(['slug' => $translatedSlug->key]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear slug columns
        Product::query()->update(['slug' => null]);

        if (Schema::hasTable('ec_products_translations')) {
            DB::table('ec_products_translations')->update(['slug' => null]);
        }
    }
};
