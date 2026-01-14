<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ec_reseller_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained('ec_customers')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('ec_orders')->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained('ec_products')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->text('reason');
            $table->string('status', 20)->default('applied')->comment('applied, reversed');
            $table->foreignId('issued_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['reseller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_reseller_penalties');
    }
};
