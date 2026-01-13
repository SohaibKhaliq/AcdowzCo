<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_shipments', function (Blueprint $table): void {
            $table->timestamp('customer_delivered_confirmed_at')->nullable()->after('date_shipped');
        });
    }

    public function down(): void
    {
        Schema::table('ec_shipments', function (Blueprint $table): void {
            $table->dropColumn('customer_delivered_confirmed_at');
        });
    }
};
