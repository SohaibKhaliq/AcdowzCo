<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_product_license_codes')) {
            return;
        }

        Schema::create('ec_product_license_codes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id');
            $table->string('license_code')->unique();
            $table->string('status', 60)->default('available');
            $table->foreignId('assigned_order_product_id')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_product_license_codes');
    }
};
