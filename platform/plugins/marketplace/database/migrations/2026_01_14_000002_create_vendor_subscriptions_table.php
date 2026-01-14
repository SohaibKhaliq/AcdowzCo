<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('mp_vendor_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('ec_customers')->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained('mp_stores')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('mp_subscription_plans')->onDelete('cascade');
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->string('status', 20)->default('active')->comment('active, expired, cancelled');
            $table->timestamps();
            
            $table->index(['customer_id', 'status']);
            $table->index(['store_id', 'status']);
            $table->index(['expires_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_vendor_subscriptions');
    }
};
