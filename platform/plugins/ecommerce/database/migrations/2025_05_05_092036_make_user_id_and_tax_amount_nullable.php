<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_orders', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->change();
            $table->decimal('tax_amount', 15)->default(0)->nullable()->change();
        });

        Schema::table('ec_order_product', function (Blueprint $table): void {
            $table->decimal('tax_amount', 15)->default(0)->nullable()->change();
        });

        Schema::table('ec_invoices', function (Blueprint $table): void {
            $table->decimal('tax_amount', 15)->default(0)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ec_orders', function (Blueprint $table): void {
            $table->foreignId('user_id')->change();
            $table->decimal('tax_amount', 15)->default(0)->change();
        });

        Schema::table('ec_order_product', function (Blueprint $table): void {
            $table->decimal('tax_amount', 15)->default(0)->change();
        });

        Schema::table('ec_invoices', function (Blueprint $table): void {
            $table->decimal('tax_amount', 15)->default(0)->change();
        });
    }
};
