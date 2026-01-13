<?php

namespace Botble\Mollie\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Concerns\Forms\HasAvailableCountriesField;
use Botble\Payment\Forms\PaymentMethodForm;

class MolliePaymentMethodForm extends PaymentMethodForm
{
    use HasAvailableCountriesField;

    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(MOLLIE_PAYMENT_METHOD_NAME)
            ->paymentName('Mollie')
            ->paymentDescription(__('Customer can buy product and pay directly using Visa, Credit card via :name', ['name' => 'Mollie']))
            ->paymentLogo(url('vendor/core/plugins/mollie/images/mollie.png'))
            ->paymentFeeField(MOLLIE_PAYMENT_METHOD_NAME)
            ->paymentUrl('https://mollie.com')
            ->paymentInstructions(view('plugins/mollie::instructions')->render())
            ->add(
                sprintf('payment_%s_api_key', MOLLIE_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(__('API Key'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('api_key', MOLLIE_PAYMENT_METHOD_NAME))
                    ->helperText(__('Get your API key from your Mollie Dashboard'))
            )
            ->add(
                sprintf('payment_%s_webhook_secret', MOLLIE_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Webhook Secret (Optional)'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('webhook_secret', MOLLIE_PAYMENT_METHOD_NAME))
                    ->helperText(__('Optional: Add a webhook secret for enhanced security. Configure this in your Mollie Dashboard under Developers > Webhooks'))
            )
            ->addAvailableCountriesField(MOLLIE_PAYMENT_METHOD_NAME);
    }
}
