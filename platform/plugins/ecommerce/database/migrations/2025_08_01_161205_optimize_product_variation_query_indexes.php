<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_product_variations', function (Blueprint $table): void {
            $table->index('configurable_product_id', 'idx_configurable_product_id');
        });

        Schema::table('ec_product_variation_items', function (Blueprint $table): void {
            $table->index('variation_id', 'idx_variation_id');
        });

        Schema::table('ec_products', function (Blueprint $table): void {
            $table->index(['is_variation', 'name', 'id'], 'idx_variation_name_id');
        });

        Schema::table('ec_product_attributes', function (Blueprint $table): void {
            if (! Schema::hasIndex('ec_product_attributes', 'idx_attribute_set_id')) {
                $table->index('attribute_set_id', 'idx_attribute_set_id');
            }

            $table->index(['attribute_set_id', 'order', 'id'], 'idx_attribute_set_order_id');
        });

        Schema::table('ec_product_attribute_sets', function (Blueprint $table): void {
            $table->index(['order', 'id'], 'idx_order_id');
        });

        Schema::table('ec_product_variation_items', function (Blueprint $table): void {
            $table->index(['variation_id', 'attribute_id'], 'idx_variation_attribute_covering');
        });
    }

    public function down(): void
    {
        Schema::table('ec_product_variations', function (Blueprint $table): void {
            $table->dropIndex('idx_configurable_product_id');
        });

        Schema::table('ec_product_variation_items', function (Blueprint $table): void {
            $table->dropIndex('idx_variation_id');
            $table->dropIndex('idx_variation_attribute_covering');
        });

        Schema::table('ec_products', function (Blueprint $table): void {
            $table->dropIndex('idx_variation_name_id');
        });

        Schema::table('ec_product_attributes', function (Blueprint $table): void {
            if (Schema::hasIndex('ec_product_attributes', 'idx_attribute_set_id')) {
                $table->dropIndex('idx_attribute_set_id');
            }
            $table->dropIndex('idx_attribute_set_order_id');
        });

        Schema::table('ec_product_attribute_sets', function (Blueprint $table): void {
            $table->dropIndex('idx_order_id');
        });
    }
};
