<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('ec_reseller_applications')) {
            Schema::create('ec_reseller_applications', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('customer_id')->constrained('ec_customers')->onDelete('cascade');
                $table->text('notes')->nullable();
                $table->string('status', 20)->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_reseller_applications');
    }
};
