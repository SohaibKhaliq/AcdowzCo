<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_product_labels', function (Blueprint $table): void {
            $table->string('text_color', 120)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ec_product_labels', function (Blueprint $table): void {
            $table->dropColumn('text_color');
        });
    }
};
