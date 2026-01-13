<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_customers', function (Blueprint $table): void {
            $table->string('stripe_account_id')->nullable();
            $table->boolean('stripe_account_active')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('ec_customers', function (Blueprint $table): void {
            $table->dropColumn(['stripe_account_id', 'stripe_account_active']);
        });
    }
};
