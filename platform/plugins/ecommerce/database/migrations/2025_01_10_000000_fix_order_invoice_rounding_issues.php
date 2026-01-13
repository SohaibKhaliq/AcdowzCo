<?php

use Botble\Ecommerce\Models\Currency;
use Botble\Ecommerce\Models\Invoice;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $this->fixOrderData();
        $this->fixInvoiceData();
    }

    public function down(): void
    {
    }

    protected function fixOrderData(): void
    {
        Order::query()->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                $currency = Currency::query()->find($order->currency_id)
                    ?: Currency::query()->where('is_default', 1)->first();
                $decimals = $currency ? (int) $currency->decimals : 2;

                $products = $order->products;
                $subtotal = 0;
                $taxTotal = 0;

                foreach ($products as $product) {
                    $product->price = round($product->price, $decimals);
                    $lineTotal = round($product->price * $product->qty, $decimals);
                    $subtotal += $lineTotal;

                    if ($product->tax_amount > 0 && $lineTotal > 0) {
                        $productModel = Product::query()->find($product->product_id);

                        $taxRate = 0;
                        if ($productModel) {
                            $taxPercentage = $productModel->total_taxes_percentage;
                            if ($taxPercentage && $taxPercentage > 0) {
                                $taxRate = $taxPercentage / 100;
                            }
                        }

                        if ($taxRate == 0) {
                            $originalLineTotal = $product->getOriginal('price') * $product->qty;
                            if ($originalLineTotal > 0) {
                                $taxRate = $product->getOriginal('tax_amount') / $originalLineTotal;
                            }
                        }

                        $product->tax_amount = round($lineTotal * $taxRate, $decimals);
                    }
                    $product->save();

                    $taxTotal += $product->tax_amount;
                }

                $order->sub_total = $subtotal;
                $order->tax_amount = $taxTotal;
                $order->shipping_amount = round($order->shipping_amount, $decimals);
                $order->discount_amount = round($order->discount_amount, $decimals);

                $order->amount = round(
                    $subtotal + $taxTotal + $order->shipping_amount - $order->discount_amount,
                    $decimals
                );

                $order->save();
            }
        });
    }

    protected function fixInvoiceData(): void
    {
        Invoice::query()->chunk(100, function ($invoices) {
            foreach ($invoices as $invoice) {
                $order = Order::query()->find($invoice->reference_id);
                if (! $order) {
                    continue;
                }

                $invoice->sub_total = $order->sub_total;
                $invoice->tax_amount = $order->tax_amount;
                $invoice->shipping_amount = $order->shipping_amount;
                $invoice->discount_amount = $order->discount_amount;
                $invoice->amount = $order->amount;
                $invoice->save();
            }
        });
    }
};
