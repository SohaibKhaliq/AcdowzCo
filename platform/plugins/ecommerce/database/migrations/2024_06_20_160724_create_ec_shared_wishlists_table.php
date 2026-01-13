<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ec_shared_wishlists')) {
            return;
        }

        Schema::create('ec_shared_wishlists', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->text('product_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_shared_wishlists');
    }
};
