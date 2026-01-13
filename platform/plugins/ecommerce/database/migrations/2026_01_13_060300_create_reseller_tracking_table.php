<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('ec_reseller_clicks')) {
            Schema::create('ec_reseller_clicks', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('reseller_id')->constrained('ec_customers')->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained('ec_products')->onDelete('set null');
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->string('referrer_url')->nullable();
                $table->timestamp('clicked_at');
                $table->index(['reseller_id', 'clicked_at']);
            });
        }

        if (! Schema::hasTable('ec_reseller_orders')) {
            Schema::create('ec_reseller_orders', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('reseller_id')->constrained('ec_customers')->onDelete('cascade');
                $table->foreignId('order_id')->constrained('ec_orders')->onDelete('cascade');
                $table->decimal('order_amount', 15, 2)->default(0);
                $table->decimal('commission_rate', 5, 2)->default(0);
                $table->decimal('commission_earned', 15, 2)->default(0);
                $table->string('status', 20)->default('pending')->comment('pending, approved, paid');
                $table->timestamps();
                $table->index(['reseller_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_reseller_orders');
        Schema::dropIfExists('ec_reseller_clicks');
    }
};
