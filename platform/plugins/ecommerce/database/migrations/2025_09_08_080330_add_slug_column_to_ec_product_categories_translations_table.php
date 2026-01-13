<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_product_categories_translations') && ! Schema::hasColumn('ec_product_categories_translations', 'slug')) {
            Schema::table('ec_product_categories_translations', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ec_product_categories_translations') && Schema::hasColumn('ec_product_categories_translations', 'slug')) {
            Schema::table('ec_product_categories_translations', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
