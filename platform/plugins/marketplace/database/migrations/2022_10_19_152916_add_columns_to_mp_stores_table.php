<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('mp_stores', 'zip_code')) {
            return;
        }

        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->string('zip_code', 20)->nullable();
            $table->string('company')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->dropColumn(['zip_code', 'company']);
        });
    }
};
