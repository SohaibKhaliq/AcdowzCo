<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            if (!Schema::hasColumn('mp_stores', 'agreement_accepted_at')) {
                $table->timestamp('agreement_accepted_at')->nullable()->after('agreement_notes')->comment('When vendor accepted the agreement');
            }
            if (!Schema::hasColumn('mp_stores', 'agreement_updated_at')) {
                $table->timestamp('agreement_updated_at')->nullable()->after('agreement_accepted_at')->comment('Last time agreement was modified');
            }
            if (!Schema::hasColumn('mp_stores', 'agreement_last_updated_by')) {
                $table->unsignedBigInteger('agreement_last_updated_by')->nullable()->after('agreement_updated_at')->comment('Admin user who last updated agreement');
            }
            if (!Schema::hasColumn('mp_stores', 'agreement_history')) {
                $table->json('agreement_history')->nullable()->after('agreement_last_updated_by')->comment('Historical agreement changes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->dropColumn([
                'agreement_accepted_at',
                'agreement_updated_at',
                'agreement_last_updated_by',
                'agreement_history',
            ]);
        });
    }
};
