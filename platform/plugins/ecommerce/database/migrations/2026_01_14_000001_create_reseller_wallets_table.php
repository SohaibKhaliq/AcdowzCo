<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ec_reseller_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->unique()->constrained('ec_customers')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0)->comment('Can be negative');
            $table->boolean('is_blocked')->default(false)->comment('Blocked if negative balance');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_reseller_wallets');
    }
};
