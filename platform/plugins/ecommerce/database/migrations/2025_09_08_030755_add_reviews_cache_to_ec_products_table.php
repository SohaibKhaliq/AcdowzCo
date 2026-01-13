<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->unsignedInteger('reviews_count')->default(0)->after('variations_count')->index();
            $table->decimal('reviews_avg', 3)->default(0)->after('reviews_count')->index();
        });

        DB::statement('
            UPDATE ec_products p
            LEFT JOIN (
                SELECT
                    product_id,
                    COUNT(*) as review_count,
                    AVG(star) as review_avg
                FROM ec_reviews
                WHERE status = "published"
                GROUP BY product_id
            ) r ON p.id = r.product_id
            SET
                p.reviews_count = COALESCE(r.review_count, 0),
                p.reviews_avg = COALESCE(r.review_avg, 0)
        ');
    }

    public function down(): void
    {
        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropColumn(['reviews_count', 'reviews_avg']);
        });
    }
};
