<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('ec_customers', function (Blueprint $table): void {
            $table->string('reseller_id', 100)->nullable()->unique()->after('is_vendor')->comment('Unique immutable reseller identifier');
            $table->boolean('is_reseller_active')->default(false)->after('reseller_id');
            $table->decimal('reseller_balance', 15, 2)->default(0)->after('is_reseller_active');
            $table->decimal('reseller_commission_rate', 5, 2)->default(5)->after('reseller_balance')->comment('Default commission percentage');

            // Phone verification
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');

            // OAuth fields
            $table->string('oauth_provider')->nullable()->after('phone_verified_at')->comment('google, facebook, etc');
            $table->string('oauth_uid')->nullable()->after('oauth_provider')->comment('OAuth provider user ID');
        });

        // Generate reseller_id for existing customers
        DB::table('ec_customers')->whereNull('reseller_id')->get()->each(function ($customer) {
            DB::table('ec_customers')
                ->where('id', $customer->id)
                ->update(['reseller_id' => 'RSL' . strtoupper(Str::random(10))]);
        });
    }

    public function down(): void
    {
        Schema::table('ec_customers', function (Blueprint $table): void {
            $table->dropColumn([
                'reseller_id',
                'is_reseller_active',
                'reseller_balance',
                'reseller_commission_rate',
                'phone_verified_at',
                'oauth_provider',
                'oauth_uid'
            ]);
        });
    }
};
