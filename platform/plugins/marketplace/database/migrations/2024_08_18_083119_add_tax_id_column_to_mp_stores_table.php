<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('mp_stores', 'tax_id')) {
            return;
        }

        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->string('tax_id')->nullable()->after('company');
        });
    }

    public function down(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->dropColumn('tax_id');
        });
    }
};
