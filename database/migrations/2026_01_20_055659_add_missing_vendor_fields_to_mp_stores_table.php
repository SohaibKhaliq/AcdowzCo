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
        Schema::table('mp_stores', function (Blueprint $table) {
            if (!Schema::hasColumn('mp_stores', 'allow_manage_shipping')) {
                $table->boolean('allow_manage_shipping')->default(false)->after('status');
            }
            if (!Schema::hasColumn('mp_stores', 'payment_term')) {
                $table->string('payment_term', 50)->default('advance')->after('allow_manage_shipping'); // advance, cod
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mp_stores', function (Blueprint $table) {
            $table->dropColumn(['allow_manage_shipping', 'payment_term']);
        });
    }
};
