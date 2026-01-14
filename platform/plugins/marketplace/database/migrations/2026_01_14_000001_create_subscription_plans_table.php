<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('mp_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('duration', 20)->comment('weekly, monthly');
            $table->decimal('price', 15, 2);
            $table->boolean('priority_boost')->default(false)->comment('Boost in search results');
            $table->boolean('verified_eligible')->default(false)->comment('Makes vendor eligible for verification');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_subscription_plans');
    }
};
