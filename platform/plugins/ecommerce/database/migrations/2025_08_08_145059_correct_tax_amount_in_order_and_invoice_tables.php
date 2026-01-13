<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('ec_order_product')
            ->whereNotNull('options')
            ->where('options', '!=', '')
            ->orderBy('id')
            ->chunk(100, function ($products) {
                foreach ($products as $product) {
                    $options = json_decode($product->options, true);

                    if (json_last_error() === JSON_ERROR_NONE && isset($options['taxRate']) && $options['taxRate'] > 0) {
                        $taxAmount = $product->price * $product->qty * $options['taxRate'] / 100;

                        DB::table('ec_order_product')
                            ->where('id', $product->id)
                            ->update(['tax_amount' => $taxAmount]);
                    }
                }
            });

        DB::table('ec_invoice_items')
            ->whereNotNull('options')
            ->where('options', '!=', '')
            ->orderBy('id')
            ->chunk(100, function ($items) {
                foreach ($items as $item) {
                    $options = json_decode($item->options, true);

                    if (json_last_error() === JSON_ERROR_NONE && isset($options['taxRate']) && $options['taxRate'] > 0) {
                        $taxAmount = $item->price * $item->qty * $options['taxRate'] / 100;

                        DB::table('ec_invoice_items')
                            ->where('id', $item->id)
                            ->update(['tax_amount' => $taxAmount]);
                    }
                }
            });
    }

    public function down(): void
    {
        // This migration corrects data, so we cannot reverse the correction
    }
};
