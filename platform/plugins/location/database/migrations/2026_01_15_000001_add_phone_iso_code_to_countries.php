<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // Add phone_code and iso_code to countries table
        if (!Schema::hasColumn('countries', 'phone_code')) {
            Schema::table('countries', function (Blueprint $table) {
                $table->string('phone_code', 10)->nullable()->after('nationality');
            });
        }

        if (!Schema::hasColumn('countries', 'iso_code')) {
            Schema::table('countries', function (Blueprint $table) {
                $table->string('iso_code', 3)->nullable()->after('phone_code');
                $table->index('iso_code');
            });
        }
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['phone_code', 'iso_code']);
        });
    }
};
