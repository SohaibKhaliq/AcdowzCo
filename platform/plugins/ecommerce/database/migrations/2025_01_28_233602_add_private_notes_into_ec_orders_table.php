<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_orders', 'private_notes')) {
            return;
        }

        Schema::table('ec_orders', function (Blueprint $table): void {
            $table->text('private_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ec_orders', function (Blueprint $table): void {
            $table->dropColumn('private_notes');
        });
    }
};
