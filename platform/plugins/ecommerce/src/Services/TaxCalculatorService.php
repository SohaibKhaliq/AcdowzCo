<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\Data\CalculateTaxData;

class TaxCalculatorService
{
    public function __construct(protected TaxRateCalculatorService $taxRateCalculator)
    {
    }

    public function execute(CalculateTaxData $input): array
    {
        if (! EcommerceHelper::isTaxEnabled()) {
            return [
                'tax_amount' => 0,
                'tax_rates' => [],
            ];
        }

        $taxRates = [];
        $taxAmount = 0;

        $products = Product::query()
            ->whereIn('id', collect($input->products)->pluck('id')->all())
            ->get();

        foreach ($input->products as $inputProduct) {
            $product = $products->firstWhere('id', $inputProduct['id']);

            if (! $product) {
                continue;
            }

            $quantity = $inputProduct['quantity'] ?? 1;
            $price = $inputProduct['price'] ?? $product->price;

            $taxRate = $this->taxRateCalculator->execute(
                $product,
                $input->country,
                $input->state,
                $input->city,
                $input->zipCode
            );

            if ($taxRate > 0) {
                $itemTax = EcommerceHelper::roundPrice($quantity * ($price * $taxRate / 100));
                $taxAmount += $itemTax;

                $taxRates[] = [
                    'product_id' => $product->id,
                    'tax_rate' => $taxRate,
                    'price' => $price,
                    'quantity' => $quantity,
                    'tax_amount' => $itemTax,
                ];
            }
        }

        return [
            'tax_amount' => $taxAmount,
            'tax_rates' => $taxRates,
        ];
    }
}
