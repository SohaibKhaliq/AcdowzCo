<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('mp_vendor_warnings')) {
            Schema::create('mp_vendor_warnings', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('store_id')->constrained('mp_stores')->onDelete('cascade');
                $table->foreignId('issued_by')->constrained('users')->comment('Admin who issued warning');
                $table->string('title');
                $table->text('content');
                $table->string('severity', 20)->default('warning')->comment('warning, critical, notice');
                $table->boolean('acknowledged')->default(false);
                $table->timestamp('acknowledged_at')->nullable();
                $table->boolean('email_sent')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_vendor_warnings');
    }
};
