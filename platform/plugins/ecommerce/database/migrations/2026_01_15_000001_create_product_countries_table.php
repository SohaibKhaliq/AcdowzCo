<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ec_product_countries')) {
            Schema::create('ec_product_countries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('ec_products')->onDelete('cascade');
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['product_id', 'country_id']);
                $table->index('product_id');
                $table->index('country_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_product_countries');
    }
};
