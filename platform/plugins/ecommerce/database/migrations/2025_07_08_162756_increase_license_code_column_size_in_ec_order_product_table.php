<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_order_product', function (Blueprint $table) {
            $table->text('license_code')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ec_order_product', function (Blueprint $table) {
            $table->string('license_code')->nullable()->change();
        });
    }
};
