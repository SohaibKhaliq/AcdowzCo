<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ec_customer_countries')) {
            Schema::create('ec_customer_countries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('ec_customers')->onDelete('cascade');
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->enum('detected_by', ['phone', 'ip', 'manual', 'default'])->default('default');
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamps();
                
                $table->index(['customer_id', 'country_id']);
                $table->index('customer_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_customer_countries');
    }
};
