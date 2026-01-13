<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('mp_stores', 'logo_square')) {
            return;
        }

        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->string('logo_square', 255)->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('mp_stores', 'logo_square')) {
            Schema::table('mp_stores', function (Blueprint $table): void {
                $table->dropColumn('logo_square');
            });
        }
    }
};
