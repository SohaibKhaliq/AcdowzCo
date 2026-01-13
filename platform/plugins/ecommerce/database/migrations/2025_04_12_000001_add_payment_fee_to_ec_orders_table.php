<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('ec_orders', 'payment_fee')) {
            Schema::table('ec_orders', function (Blueprint $table): void {
                $table->decimal('payment_fee', 15)->default(0)->nullable()->after('shipping_amount');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ec_orders', 'payment_fee')) {
            Schema::table('ec_orders', function (Blueprint $table): void {
                $table->dropColumn('payment_fee');
            });
        }
    }
};
