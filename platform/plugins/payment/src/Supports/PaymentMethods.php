<?php

namespace Botble\Payment\Supports;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Events\RenderedPaymentMethods;
use Botble\Payment\Events\RenderingPaymentMethods;

class PaymentMethods
{
    protected array $methods = [];

    protected array $excludedMethods = [];

    public function method(string $name, array $args = []): self
    {
        $args = array_merge(['html' => null, 'priority' => count($this->methods) + 1], $args);

        $this->methods[$name] = $args;

        return $this;
    }

    public function methods(): array
    {
        return $this->methods;
    }

    public function removeMethod(string $name): self
    {
        unset($this->methods[$name]);

        return $this;
    }

    public function excludeMethod(string $name): self
    {
        $this->excludedMethods[] = $name;

        return $this;
    }

    public function includeMethod(string $name): self
    {
        $this->excludedMethods = array_filter($this->excludedMethods, function ($method) use ($name) {
            return $method !== $name;
        });

        return $this;
    }

    public function getExcludedMethods(): array
    {
        return $this->excludedMethods;
    }

    public function isMethodExcluded(string $name): bool
    {
        return in_array($name, $this->excludedMethods);
    }

    public function clearExcludedMethods(): self
    {
        $this->excludedMethods = [];

        return $this;
    }

    public function getDefaultMethod(): ?string
    {
        return setting('default_payment_method', PaymentMethodEnum::COD);
    }

    public function getSelectedMethod(): ?string
    {
        return session('selected_payment_method', $this->getDefaultMethod());
    }

    public function getSelectingMethod(): ?string
    {
        return $this->getSelectedMethod() ?: $this->getDefaultMethod();
    }

    public function render(): string
    {
        $this->methods = [
            PaymentMethodEnum::COD => [
                'html' => view('plugins/payment::partials.cod')->render(),
                'priority' => 998,
            ],
            PaymentMethodEnum::BANK_TRANSFER => [
                'html' => view('plugins/payment::partials.bank-transfer')->render(),
                'priority' => 999,
            ],
        ] + $this->methods;

        $externalExcludedMethods = apply_filters('payment_methods_excluded', []);
        $allExcludedMethods = array_merge($this->excludedMethods, (array) $externalExcludedMethods);

        foreach ($allExcludedMethods as $excludedMethod) {
            unset($this->methods[$excludedMethod]);
        }

        $methods = collect($this->methods)->sortBy('priority');
        $defaultMethod = $methods->pull(PaymentHelper::defaultPaymentMethod());

        if ($defaultMethod) {
            $methods = $methods->prepend($defaultMethod, PaymentHelper::defaultPaymentMethod());
        }

        event(new RenderingPaymentMethods($methods->all()));

        $country = apply_filters('payment_checkout_country', null);

        $html = '';

        foreach ($methods as $name => $method) {
            if (! get_payment_setting('status', $name) == 1) {
                continue;
            }

            $availableCountries = PaymentHelper::getAvailableCountries($name);

            if ($country && ! in_array($country, $availableCountries) && ! in_array($country, array_keys($availableCountries))) {
                continue;
            }

            $html .= $method['html'];
        }

        event(new RenderedPaymentMethods($html));

        return $html;
    }
}
