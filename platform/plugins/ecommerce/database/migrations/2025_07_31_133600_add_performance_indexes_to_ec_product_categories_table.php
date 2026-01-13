<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_product_categories', function (Blueprint $table) {
            $table->index(['status', 'order'], 'idx_categories_status_order');
            $table->index('order', 'idx_categories_order');
        });
    }

    public function down(): void
    {
        Schema::table('ec_product_categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_status_order');
            $table->dropIndex('idx_categories_order');
        });
    }
};
