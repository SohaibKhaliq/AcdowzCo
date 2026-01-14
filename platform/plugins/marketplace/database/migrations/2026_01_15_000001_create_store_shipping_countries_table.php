<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('mp_store_shipping_countries')) {
            Schema::create('mp_store_shipping_countries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained('mp_stores')->onDelete('cascade');
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->unique(['store_id', 'country_id']);
                $table->index('store_id');
                $table->index('country_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_store_shipping_countries');
    }
};
