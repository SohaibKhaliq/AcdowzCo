<?php

use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('ec_invoice_items');
        Schema::dropIfExists('ec_invoices');

        Schema::create('ec_invoices', function (Blueprint $table): void {
            $table->id();
            $table->morphs('reference');
            $table->string('code')->unique();
            $table->string('customer_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_tax_id')->nullable();
            $table->decimal('sub_total', 15)->unsigned();
            $table->decimal('tax_amount', 15)->default(0)->unsigned();
            $table->decimal('shipping_amount', 15)->default(0)->unsigned();
            $table->decimal('discount_amount', 15)->default(0)->unsigned();
            $table->string('shipping_option', 60)->nullable();
            $table->string('shipping_method', 60)->default('default');
            $table->string('coupon_code', 120)->nullable();
            $table->string('discount_description')->nullable();
            $table->decimal('amount', 15)->unsigned();
            $table->text('description')->nullable();
            $table->foreignId('payment_id')->nullable()->index();
            $table->string('status')->index()->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ec_invoice_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id');
            $table->morphs('reference');
            $table->string('name');
            $table->string('description', 400)->nullable();
            $table->string('image')->nullable();
            $table->integer('qty')->unsigned();
            $table->decimal('sub_total', 15)->unsigned();
            $table->decimal('tax_amount', 15)->default(0)->unsigned();
            $table->decimal('discount_amount', 15)->default(0)->unsigned();
            $table->decimal('amount', 15)->unsigned();
            $table->text('options')->nullable();
            $table->timestamps();
        });

        try {
            foreach (Order::with('invoice')->where('is_finished', 1)->get() as $order) {
                if ($order->invoice->id) {
                    continue;
                }

                /**
                 * @var Order $order
                 */
                InvoiceHelper::store($order);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ec_invoice_items');
        Schema::dropIfExists('ec_invoices');
    }
};
