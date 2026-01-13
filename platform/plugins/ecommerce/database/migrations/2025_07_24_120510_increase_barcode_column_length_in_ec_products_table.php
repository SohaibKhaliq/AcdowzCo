<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_products', 'barcode')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->string('barcode', 150)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ec_products', 'barcode')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->string('barcode', 50)->nullable()->change();
            });
        }
    }
};
