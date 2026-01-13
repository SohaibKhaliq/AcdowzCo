<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_taxes_translations')) {
            return;
        }

        Schema::create('ec_taxes_translations', function (Blueprint $table): void {
            $table->string('lang_code');
            $table->foreignId('ec_taxes_id');
            $table->string('title')->nullable();

            $table->primary(['lang_code', 'ec_taxes_id'], 'ec_taxes_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_taxes_translations');
    }
};
