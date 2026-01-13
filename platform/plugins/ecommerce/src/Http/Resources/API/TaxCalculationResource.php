<?php

namespace Botble\Ecommerce\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxCalculationResource extends JsonResource
{
    public function toArray($request): array
    {
        $items = collect($this->resource['tax_rates'])->map(function ($item) {
            $subTotal = $item['quantity'] * $item['price'];

            return [
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'price_formatted' => format_price($item['price']),
                'quantity' => $item['quantity'],
                'tax_rate' => $item['tax_rate'],
                'tax_amount' => round($item['tax_amount'], 2),
                'tax_amount_formatted' => format_price($item['tax_amount']),
                'subtotal' => round($subTotal, 2),
                'subtotal_formatted' => format_price($subTotal),
                'total' => round($subTotal + $item['tax_amount'], 2),
                'total_formatted' => format_price($subTotal + $item['tax_amount']),
            ];
        });

        $subTotal = $items->sum('subtotal');
        $taxAmount = round($this->resource['tax_amount'], 2);
        $total = round($subTotal + $taxAmount, 2);

        return [
            'items' => $items,
            'totals' => [
                'sub_total' => round($subTotal, 2),
                'sub_total_formatted' => format_price($subTotal),
                'tax_amount' => $taxAmount,
                'tax_amount_formatted' => format_price($taxAmount),
                'total' => $total,
                'total_formatted' => format_price($total),
            ],
        ];
    }
}
