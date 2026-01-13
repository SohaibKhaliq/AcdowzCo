<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->string('agreement_type', 20)->default('commission')->after('status')->comment('flat_fee or commission');
            $table->decimal('agreement_value', 15, 2)->default(0)->after('agreement_type')->comment('Flat fee amount or commission percentage');
            $table->text('agreement_notes')->nullable()->after('agreement_value');
        });
    }

    public function down(): void
    {
        Schema::table('mp_stores', function (Blueprint $table): void {
            $table->dropColumn(['agreement_type', 'agreement_value', 'agreement_notes']);
        });
    }
};
