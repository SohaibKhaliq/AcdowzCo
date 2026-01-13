<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        try {
            Schema::table('ec_customers', function (Blueprint $table): void {
                $table->dropUnique('ec_customers_email_unique');
                $table->string('email')->nullable()->change();
            });
        } catch (Throwable) {
            // Do nothing
        }
    }

    public function down(): void
    {
        Schema::table('ec_customers', function (Blueprint $table): void {
            $table->string('email')->unique()->change();
        });
    }
};
