<?php

namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class HandleTaxService
{
    public function __construct(protected TaxRateCalculatorService $taxRateCalculator)
    {
    }

    public function execute(Collection $products, array $data = []): Collection
    {
        if (! EcommerceHelper::isTaxEnabled()) {
            return $products;
        }

        if (EcommerceHelper::isUsingInMultipleCountries()) {
            $country = Arr::get($data, 'country');
        } else {
            $country = EcommerceHelper::getFirstCountryId();
        }

        $city = Arr::get($data, 'city');
        $state = Arr::get($data, 'state');

        $zipCode = null;
        if (EcommerceHelper::isZipCodeEnabled()) {
            $zipCode = Arr::get($data, 'zip_code');
        }

        if ($zipCode || ($country || $state || $city)) {
            $cartItems = Cart::instance('cart')->content();

            foreach ($products as $product) {
                $cartItem = $cartItems->where('id', $product->getKey())->first();

                $taxRate = $this->taxRate($product, $country, $state, $city, $zipCode);

                if ($taxRate != $cartItem->taxRate) {
                    Cart::instance('cart')->setTax($cartItem->rowId, $taxRate);
                }
            }
        }

        return $products;
    }

    public function taxRate(Product $product, ?string $country = null, ?string $state = null, ?string $city = null, ?string $zipCode = null): float
    {
        return $this->taxRateCalculator->execute($product, $country, $state, $city, $zipCode);
    }
}
