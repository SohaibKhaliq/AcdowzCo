<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_discounts', function (Blueprint $table): void {
            $table->boolean('can_use_with_flash_sale')->default(false)->after('can_use_with_promotion');
        });
    }

    public function down(): void
    {
        Schema::table('ec_discounts', function (Blueprint $table): void {
            $table->dropColumn('can_use_with_flash_sale');
        });
    }
};
