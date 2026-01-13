<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->index(['store_id', 'is_variation', 'status'], 'idx_store_variation_status');
        });

        Schema::table('ec_product_category_product', function (Blueprint $table) {
            $table->index(['product_id', 'category_id'], 'idx_product_category_composite');
        });
    }

    public function down(): void
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropIndex('idx_store_variation_status');
        });

        Schema::table('ec_product_category_product', function (Blueprint $table) {
            $table->dropIndex('idx_product_category_composite');
        });
    }
};
