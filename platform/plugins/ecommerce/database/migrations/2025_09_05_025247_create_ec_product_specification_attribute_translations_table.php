<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_product_specification_attribute_translations')) {
            return;
        }

        Schema::create('ec_product_specification_attribute_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->index('psat_product_id_index');
            $table->foreignId('attribute_id')->index('psat_attribute_id_index');
            $table->string('lang_code', 20);
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'attribute_id', 'lang_code'], 'psat_unique');
            $table->index(['product_id', 'attribute_id'], 'psat_product_attribute_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_product_specification_attribute_translations');
    }
};
