<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_review_replies')) {
            return;
        }

        Schema::create('ec_review_replies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('review_id');
            $table->text('message');
            $table->timestamps();

            $table->unique(['review_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_review_replies');
    }
};
