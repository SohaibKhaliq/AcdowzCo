<?php

namespace Botble\Razorpay\Forms;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Concerns\Forms\HasAvailableCountriesField;
use Botble\Payment\Forms\PaymentMethodForm;

class RazorpayPaymentMethodForm extends PaymentMethodForm
{
    use HasAvailableCountriesField;

    public function setup(): void
    {
        parent::setup();

        $this
            ->paymentId(RAZORPAY_PAYMENT_METHOD_NAME)
            ->paymentName('Razorpay')
            ->paymentDescription(__('Customer can buy product and pay directly using Visa, Credit card via :name', ['name' => 'Razorpay']))
            ->paymentLogo(url('vendor/core/plugins/razorpay/images/razorpay.svg'))
            ->paymentFeeField(RAZORPAY_PAYMENT_METHOD_NAME)
            ->paymentUrl('https://razorpay.com')
            ->paymentInstructions(view('plugins/razorpay::instructions')->render())
            ->add(
                sprintf('payment_%s_key', RAZORPAY_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Key'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME))
            )
            ->add(
                sprintf('payment_%s_secret', RAZORPAY_PAYMENT_METHOD_NAME),
                'password',
                TextFieldOption::make()
                    ->label(__('Secret'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME))
            )
            ->add(
                'payment_' . RAZORPAY_PAYMENT_METHOD_NAME . '_payment_type',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Payment Type'))
                    ->choices([
                        'hosted_checkout' => 'Hosted Checkout',
                        'website_embedded' => 'Website Embedded',
                    ])
                    ->selected(get_payment_setting(
                        'payment_type',
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        'hosted_checkout',
                    ))
            )
            ->add(
                sprintf('payment_%s_webhook_secret', RAZORPAY_PAYMENT_METHOD_NAME),
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Webhook Secret'))
                    ->helperText(__('Get webhook secret key from your Razorpay dashboard to verify webhook requests'))
                    ->value(BaseHelper::hasDemoModeEnabled() ? '*******************************' : get_payment_setting('webhook_secret', RAZORPAY_PAYMENT_METHOD_NAME))
            )
            ->addAvailableCountriesField(RAZORPAY_PAYMENT_METHOD_NAME);
    }
}
