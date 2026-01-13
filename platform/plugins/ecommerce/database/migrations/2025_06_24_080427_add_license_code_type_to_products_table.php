<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('ec_products', 'license_code_type')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->enum('license_code_type', ['auto_generate', 'pick_from_list'])->default('auto_generate')->after('generate_license_code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ec_products', 'license_code_type')) {
            Schema::table('ec_products', function (Blueprint $table) {
                $table->dropColumn('license_code_type');
            });
        }
    }
};
