<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('ec_specification_groups_translations')) {
            Schema::create('ec_specification_groups_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('ec_specification_groups_id');
                $table->string('name')->nullable();
                $table->string('description', 400)->nullable();

                $table->primary(['lang_code', 'ec_specification_groups_id'], 'ec_specification_groups_translations_primary');
            });
        }

        if (! Schema::hasTable('ec_specification_tables_translations')) {
            Schema::create('ec_specification_tables_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('ec_specification_tables_id');
                $table->string('name')->nullable();
                $table->string('description', 400)->nullable();

                $table->primary(['lang_code', 'ec_specification_tables_id'], 'ec_specification_tables_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_specification_tables_translations');
        Schema::dropIfExists('ec_specification_groups_translations');
    }
};
