<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            // Add subscription plan relationship
            if (!Schema::hasColumn('mp_stores', 'subscription_plan_id')) {
                $table->foreignId('subscription_plan_id')
                    ->nullable()
                    ->after('agreement_notes')
                    ->comment('For subscription-based vendors')
                    ->constrained('mp_subscription_plans')
                    ->nullOnDelete();
            }

            // Add commission rate field (separate from agreement_value for flexibility)
            if (!Schema::hasColumn('mp_stores', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)
                    ->default(0)
                    ->after('subscription_plan_id')
                    ->comment('Commission percentage for this vendor');
            }

            // Add agreement audit trail
            if (!Schema::hasColumn('mp_stores', 'agreement_accepted_at')) {
                $table->timestamp('agreement_accepted_at')
                    ->nullable()
                    ->after('commission_rate')
                    ->comment('When vendor accepted the agreement');
            }

            if (!Schema::hasColumn('mp_stores', 'agreement_updated_at')) {
                $table->timestamp('agreement_updated_at')
                    ->nullable()
                    ->after('agreement_accepted_at')
                    ->comment('Last time agreement was modified');
            }

            if (!Schema::hasColumn('mp_stores', 'agreement_last_updated_by')) {
                $table->foreignId('agreement_last_updated_by')
                    ->nullable()
                    ->after('agreement_updated_at')
                    ->comment('Admin user who last updated agreement');
            }

            // Add agreement history tracking
            if (!Schema::hasColumn('mp_stores', 'agreement_history')) {
                $table->json('agreement_history')
                    ->nullable()
                    ->after('agreement_last_updated_by')
                    ->comment('JSON history of agreement changes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->dropColumn([
                'subscription_plan_id',
                'commission_rate',
                'agreement_accepted_at',
                'agreement_updated_at',
                'agreement_last_updated_by',
                'agreement_history',
            ]);
        });
    }
};
