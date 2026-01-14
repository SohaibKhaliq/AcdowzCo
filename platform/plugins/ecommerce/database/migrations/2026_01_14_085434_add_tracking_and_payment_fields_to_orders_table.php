<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->string('tracking_id', 191)->nullable()->after('token');
            $table->string('payment_method', 60)->nullable()->after('tracking_id');
            $table->string('payment_status', 60)->default('pending')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ec_orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_id', 'payment_method', 'payment_status']);
        });
    }
};
