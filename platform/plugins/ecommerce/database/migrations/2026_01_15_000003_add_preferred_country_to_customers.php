<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // Add current_country_id to sessions for guest users
        if (!Schema::hasColumn('ec_customers', 'preferred_country_id')) {
            Schema::table('ec_customers', function (Blueprint $table) {
                $table->foreignId('preferred_country_id')->nullable()->after('status')->constrained('countries')->onDelete('set null');
                $table->index('preferred_country_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('ec_customers', function (Blueprint $table) {
            $table->dropForeign(['preferred_country_id']);
            $table->dropColumn('preferred_country_id');
        });
    }
};
