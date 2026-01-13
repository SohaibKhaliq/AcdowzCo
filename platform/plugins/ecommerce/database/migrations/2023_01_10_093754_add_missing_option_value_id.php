<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        rescue(function (): void {
            Schema::table('ec_option_value', function (Blueprint $table): void {
                $table->id()->after('option_id');
            });

            Schema::table('ec_global_option_value', function (Blueprint $table): void {
                $table->id()->after('option_id');
            });
        }, report: false);
    }

    public function down(): void
    {
        Schema::table('ec_global_option_value', function (Blueprint $table): void {
            $table->dropColumn('id');
        });

        Schema::table('ec_option_value', function (Blueprint $table): void {
            $table->dropColumn('id');
        });
    }
};
