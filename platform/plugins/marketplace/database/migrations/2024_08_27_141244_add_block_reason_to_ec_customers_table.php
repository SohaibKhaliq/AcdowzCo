<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_customers', 'block_reason')) {
            return;
        }

        Schema::table('ec_customers', function (Blueprint $table): void {
            $table->string('block_reason', 400)->nullable()->after('status');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('ec_customers', 'block_reason')) {
            Schema::table('ec_customers', function (Blueprint $table): void {
                $table->dropColumn('block_reason');
            });
        }
    }
};
