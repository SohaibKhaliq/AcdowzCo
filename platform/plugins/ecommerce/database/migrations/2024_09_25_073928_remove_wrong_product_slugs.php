<?php

use Botble\Ecommerce\Models\Product;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        try {
            Slug::query()
                ->where('reference_type', Product::class)
                ->join('ec_products', 'ec_products.id', '=', 'slugs.reference_id')
                ->where('ec_products.is_variation', true)
                ->delete();

            Slug::query()
                ->where('reference_type', Product::class)
                ->whereNotIn('reference_id', function ($query): void {
                    $query
                        ->select('id')
                        ->from('ec_products')
                        ->where('is_variation', false);
                })
                ->delete();
        } catch (Throwable) {
            // Ignore
        }
    }
};
