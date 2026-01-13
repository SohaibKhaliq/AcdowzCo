<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_specification_groups', 'author_id')) {
            return;
        }

        Schema::table('ec_specification_groups', function (Blueprint $table): void {
            $table->nullableMorphs('author');
        });

        Schema::table('ec_specification_attributes', function (Blueprint $table): void {
            $table->nullableMorphs('author');
        });

        Schema::table('ec_specification_tables', function (Blueprint $table): void {
            $table->nullableMorphs('author');
        });
    }

    public function down(): void
    {
        Schema::table('ec_specification_groups', function (Blueprint $table): void {
            $table->dropMorphs('author');
        });

        Schema::table('ec_specification_attributes', function (Blueprint $table): void {
            $table->dropMorphs('author');
        });

        Schema::table('ec_specification_tables', function (Blueprint $table): void {
            $table->dropMorphs('author');
        });
    }
};
