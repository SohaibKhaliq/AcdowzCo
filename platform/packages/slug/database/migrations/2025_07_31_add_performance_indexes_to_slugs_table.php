<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('slugs', function (Blueprint $table) {
            $table->index(['reference_type', 'reference_id'], 'idx_slugs_reference');
        });
    }

    public function down(): void
    {
        Schema::table('slugs', function (Blueprint $table) {
            $table->dropIndex('idx_slugs_reference');
        });
    }
};
