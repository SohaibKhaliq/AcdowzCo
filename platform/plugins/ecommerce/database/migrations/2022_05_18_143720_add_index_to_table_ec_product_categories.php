<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_product_categories', function (Blueprint $table): void {
            $table->index(['parent_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('ec_product_categories', function (Blueprint $table): void {
            $table->dropIndex(['parent_id', 'status']);
        });
    }
};
