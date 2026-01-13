<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('ec_products', 'barcode')) {
            Schema::table('ec_products', function (Blueprint $table): void {
                $table->string('barcode', 50)->unique()->nullable();
            });
        }

        if (! Schema::hasColumn('ec_products', 'cost_per_item')) {
            Schema::table('ec_products', function (Blueprint $table): void {
                $table->double('cost_per_item')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ec_products', 'barcode')) {
            Schema::table('ec_products', function (Blueprint $table): void {
                $table->dropColumn('barcode');
            });
        }

        if (Schema::hasColumn('ec_products', 'cost_per_item')) {
            Schema::table('ec_products', function (Blueprint $table): void {
                $table->dropColumn('cost_per_item');
            });
        }
    }
};
