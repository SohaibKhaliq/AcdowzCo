<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('mp_vendor_info', 'payout_payment_method')) {
            return;
        }

        Schema::table('mp_vendor_info', function (Blueprint $table): void {
            $table->string('payout_payment_method', 120)->nullable()->default('bank_transfer');
        });
    }

    public function down(): void
    {
        Schema::table('mp_vendor_info', function (Blueprint $table): void {
            $table->dropColumn('payout_payment_method');
        });
    }
};
