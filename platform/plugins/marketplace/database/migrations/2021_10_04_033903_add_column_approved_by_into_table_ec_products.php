<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_products', 'approved_by')) {
            return;
        }

        Schema::table('ec_products', function (Blueprint $table): void {
            $table->foreignId('approved_by')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('ec_products', function (Blueprint $table): void {
            $table->dropColumn('approved_by');
        });
    }
};
