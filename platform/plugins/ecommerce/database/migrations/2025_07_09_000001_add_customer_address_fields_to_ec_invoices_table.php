<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('ec_invoices', 'customer_country')) {
            Schema::table('ec_invoices', function (Blueprint $table): void {
                $table->string('customer_country')->nullable()->after('customer_address');
                $table->string('customer_state')->nullable()->after('customer_country');
                $table->string('customer_city')->nullable()->after('customer_state');
                $table->string('customer_zip_code')->nullable()->after('customer_city');
                $table->string('customer_address_line')->nullable()->after('customer_zip_code');
            });
        }
    }

    public function down(): void
    {
        Schema::table('ec_invoices', function (Blueprint $table): void {
            $table->dropColumn([
                'customer_country',
                'customer_state',
                'customer_city',
                'customer_zip_code',
                'customer_address_line',
            ]);
        });
    }
};
