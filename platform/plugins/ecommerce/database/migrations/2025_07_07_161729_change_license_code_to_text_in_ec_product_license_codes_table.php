<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_product_license_codes', function (Blueprint $table) {
            try {
                $table->dropUnique(['license_code']);
            } catch (Exception $e) {
            }

            $table->text('license_code')->change();
        });
    }

    public function down(): void
    {
        Schema::table('ec_product_license_codes', function (Blueprint $table) {
            $table->string('license_code')->change();

            try {
                $table->unique('license_code');
            } catch (Exception $e) {
            }
        });
    }
};
