<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ec_customers', function (Blueprint $table) {
            $table->timestamp('reseller_deleted_at')->nullable()->after('reseller_deletion_requested_at');
            $table->unsignedBigInteger('reseller_deleted_by')->nullable()->after('reseller_deleted_at');
            $table->timestamp('reseller_disabled_at')->nullable()->after('reseller_deleted_by');
            $table->unsignedBigInteger('reseller_disabled_by')->nullable()->after('reseller_disabled_at');
            $table->text('reseller_disable_reason')->nullable()->after('reseller_disabled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ec_customers', function (Blueprint $table) {
            $table->dropColumn([
                'reseller_deleted_at',
                'reseller_deleted_by',
                'reseller_disabled_at',
                'reseller_disabled_by',
                'reseller_disable_reason',
            ]);
        });
    }
};
