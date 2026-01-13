<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasIndex('ec_products', 'idx_products_status_variation')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->index(['status', 'is_variation', 'id'], 'idx_products_status_variation');
            });
        }

        if (! Schema::hasIndex('ec_product_variations', 'idx_product_variations_config')) {
            Schema::table('ec_product_variations', function (Blueprint $table) {
                $table->index(['configurable_product_id', 'is_default'], 'idx_product_variations_config');
            });
        }

        if (! Schema::hasIndex('ec_product_category_product', 'idx_product_category')) {
            Schema::table('ec_product_category_product', function (Blueprint $table) {
                $table->index(['product_id', 'category_id'], 'idx_product_category');
            });
        }

        if (! Schema::hasIndex('ec_product_cross_sale_relations', 'idx_product_cross_sale')) {
            Schema::table('ec_product_cross_sale_relations', function (Blueprint $table) {
                $table->index(['from_product_id', 'to_product_id'], 'idx_product_cross_sale');
            });
        }

        if (! Schema::hasIndex('ec_products', 'idx_products_price_sale')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->index(['sale_type', 'sale_price', 'price'], 'idx_products_price_sale');
            });
        }

        if (! Schema::hasIndex('ec_products', 'idx_products_order_created')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->index(['order', 'created_at'], 'idx_products_order_created');
            });
        }

        if (! Schema::hasIndex('ec_products', 'idx_products_stock')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->index(['with_storehouse_management', 'stock_status', 'quantity'], 'idx_products_stock');
            });
        }
    }

    public function down(): void
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropIndex('idx_products_status_variation');
            $table->dropIndex('idx_products_price_sale');
            $table->dropIndex('idx_products_order_created');
            $table->dropIndex('idx_products_stock');
        });

        Schema::table('ec_product_variations', function (Blueprint $table) {
            $table->dropIndex('idx_product_variations_config');
        });

        Schema::table('ec_product_category_product', function (Blueprint $table) {
            $table->dropIndex('idx_product_category');
        });

        Schema::table('ec_product_cross_sale_relations', function (Blueprint $table) {
            $table->dropIndex('idx_product_cross_sale');
        });
    }
};
