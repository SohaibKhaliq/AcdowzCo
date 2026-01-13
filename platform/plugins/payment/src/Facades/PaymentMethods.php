<?php

namespace Botble\Payment\Facades;

use Botble\Payment\Supports\PaymentMethods as PaymentMethodsSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Botble\Payment\Supports\PaymentMethods method(string $name, array $args = [])
 * @method static array methods()
 * @method static \Botble\Payment\Supports\PaymentMethods removeMethod(string $name)
 * @method static \Botble\Payment\Supports\PaymentMethods excludeMethod(string $name)
 * @method static \Botble\Payment\Supports\PaymentMethods includeMethod(string $name)
 * @method static array getExcludedMethods()
 * @method static bool isMethodExcluded(string $name)
 * @method static \Botble\Payment\Supports\PaymentMethods clearExcludedMethods()
 * @method static string|null getDefaultMethod()
 * @method static string|null getSelectedMethod()
 * @method static string|null getSelectingMethod()
 * @method static string render()
 *
 * @see \Botble\Payment\Supports\PaymentMethods
 */
class PaymentMethods extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaymentMethodsSupport::class;
    }
}
