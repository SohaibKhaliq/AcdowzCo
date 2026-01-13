<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('mp_category_sale_commissions')) {
            return;
        }

        Schema::create('mp_category_sale_commissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_category_id')->unique();
            $table->decimal('commission_percentage')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_category_sale_commissions');
    }
};
