<?php

namespace Botble\Ecommerce\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Tax;

class TaxRateCalculatorService
{
    public function execute(
        Product $product,
        ?string $country = null,
        ?string $state = null,
        ?string $city = null,
        ?string $zipCode = null
    ): float {
        $taxRate = 0;
        $taxes = $product->taxes->where('status', BaseStatusEnum::PUBLISHED);

        if ($taxes->isNotEmpty()) {
            foreach ($taxes as $tax) {
                if ($tax->rules && $tax->rules->isNotEmpty()) {
                    $rule = null;
                    if ($zipCode) {
                        $rule = $tax->rules->firstWhere('zip_code', $zipCode);
                    }

                    if (! $rule && $country && $state && $city) {
                        $rule = $tax->rules
                            ->where('country', $country)
                            ->where('state', $state)
                            ->where('city', $city)
                            ->first();
                    }

                    if (! $rule && $country && $state) {
                        $rule = $tax->rules
                            ->where('country', $country)
                            ->where('state', $state)
                            ->whereNull('city')
                            ->first();
                    }

                    if (! $rule && $country) {
                        $rule = $tax->rules
                            ->where('country', $country)
                            ->whereNull('state')
                            ->whereNull('city')
                            ->first();
                    }

                    if ($rule) {
                        $taxRate += $rule->percentage;
                    } else {
                        $taxRate += $tax->percentage;
                    }
                } else {
                    $taxRate += $tax->percentage;
                }
            }
        } elseif ($defaultTaxRate = get_ecommerce_setting('default_tax_rate')) {
            $taxRate = Tax::query()->where('id', $defaultTaxRate)->value('percentage');
        }

        return (float) $taxRate;
    }
}
