<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('mp_vendor_info', 'tax_info')) {
            return;
        }

        Schema::table('mp_vendor_info', function (Blueprint $table): void {
            $table->text('tax_info')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mp_vendor_info', function (Blueprint $table): void {
            $table->dropColumn('tax_info');
        });
    }
};
