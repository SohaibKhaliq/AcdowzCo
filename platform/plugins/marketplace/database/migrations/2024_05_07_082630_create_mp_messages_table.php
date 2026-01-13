<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('mp_messages')) {
            return;
        }

        Schema::create('mp_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('store_id');
            $table->foreignId('customer_id')->nullable();
            $table->string('name', 60);
            $table->string('email', 60);
            $table->longText('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_messages');
    }
};
