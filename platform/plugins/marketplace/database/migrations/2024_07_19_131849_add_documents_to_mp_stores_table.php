<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('mp_stores', 'certificate_file')) {
            return;
        }

        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->string('certificate_file')->nullable();
            $table->string('government_id_file')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->dropColumn(['certificate_file', 'government_id_file']);
        });
    }
};
