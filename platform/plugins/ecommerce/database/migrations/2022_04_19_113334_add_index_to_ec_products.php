<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_products', function (Blueprint $table): void {
            $table->index(['brand_id', 'status', 'is_variation', 'created_at']);
        });

        Schema::table('ec_reviews', function (Blueprint $table): void {
            $table->index(['product_id', 'customer_id', 'status', 'created_at']);
        });

        Schema::table('ec_product_categories', function (Blueprint $table): void {
            $table->index(['parent_id', 'status', 'created_at']);
        });

        Schema::table('ec_orders', function (Blueprint $table): void {
            $table->index(['user_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('ec_products', function (Blueprint $table): void {
            $table->dropIndex(['brand_id', 'status', 'is_variation', 'created_at']);
        });

        Schema::table('ec_reviews', function (Blueprint $table): void {
            $table->dropIndex(['product_id', 'customer_id', 'status', 'created_at']);
        });

        Schema::table('ec_product_categories', function (Blueprint $table): void {
            $table->dropIndex(['parent_id', 'status', 'created_at']);
        });

        Schema::table('ec_orders', function (Blueprint $table): void {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });
    }
};
