<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->unsignedInteger('variations_count')->default(0)->after('is_variation')->index();
        });

        DB::statement('
            UPDATE ec_products p
            SET variations_count = (
                SELECT COUNT(*)
                FROM ec_product_variations pv
                WHERE pv.configurable_product_id = p.id
            )
            WHERE p.is_variation = 0
        ');
    }

    public function down(): void
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropColumn('variations_count');
        });
    }
};
