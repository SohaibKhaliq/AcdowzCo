<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {

        if (Schema::hasTable('ec_abandoned_carts')) {
            return;
        }

        Schema::create('ec_abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('session_id')->nullable()->index();
            $table->json('cart_data');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->integer('items_count')->default(0);
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('customer_name')->nullable();
            $table->timestamp('abandoned_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->integer('reminders_sent')->default(0);
            $table->boolean('is_recovered')->default(false);
            $table->timestamp('recovered_at')->nullable();
            $table->unsignedBigInteger('recovered_order_id')->nullable()->index();
            $table->timestamps();

            $table->index(['abandoned_at', 'is_recovered']);
            $table->index(['created_at', 'is_recovered']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_abandoned_carts');
    }
};
