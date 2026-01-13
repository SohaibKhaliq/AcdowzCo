<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ec_customers', function (Blueprint $table): void {
            if (!Schema::hasColumn('ec_customers', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('confirmed_at');
            }
            if (!Schema::hasColumn('ec_customers', 'oauth_provider')) {
                $table->string('oauth_provider', 50)->nullable()->after('phone_verified_at')->comment('google, facebook, phone');
            }
            if (!Schema::hasColumn('ec_customers', 'oauth_uid')) {
                $table->string('oauth_uid')->nullable()->after('oauth_provider');
                $table->index(['oauth_provider', 'oauth_uid']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('ec_customers', function (Blueprint $table): void {
            $table->dropColumn(['phone_verified_at', 'oauth_provider', 'oauth_uid']);
        });
    }
};
