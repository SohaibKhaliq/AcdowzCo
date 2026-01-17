<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Http\Requests\Settings\EcommerceSettingRequest;
use Botble\Setting\Forms\SettingForm;

class EnhancedCustomerSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.customer.title'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.customer.description'))
            ->setValidatorClass(EcommerceSettingRequest::class)
            ->add('login_option', 'customSelect', [
                'label' => trans('plugins/ecommerce::setting.customer.form.login_option'),
                'choices' => [
                    'email' => trans('plugins/ecommerce::setting.customer.form.login_option_email'),
                    'phone' => trans('plugins/ecommerce::setting.customer.form.login_option_phone'),
                    'email_or_phone' => trans('plugins/ecommerce::setting.customer.form.login_option_email_or_phone'),
                ],
                'value' => get_ecommerce_setting('login_option', 'email'),
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.customer.form.login_option_helper'),
                ],
            ])

            // Google OAuth Settings
            ->add('oauth_section_title', 'html', [
                'html' => '<h5 class="mt-4">' . trans('OAuth Authentication') . '</h5>',
            ])
            ->add(
                'google_oauth_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('Enable Google OAuth Login'))
                    ->helperText(trans('Allow customers to login using their Google account'))
                    ->value(get_ecommerce_setting('google_oauth_enabled', false))
            )
            ->add(
                'google_client_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('Google Client ID'))
                    ->helperText(trans('Get this from Google Cloud Console'))
                    ->value(config('services.google.client_id'))
            )
            ->add(
                'google_client_secret',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('Google Client Secret'))
                    ->helperText(trans('Get this from Google Cloud Console'))
                    ->value(config('services.google.client_secret'))
            )

            // Reseller System Settings
            ->add('reseller_section_title', 'html', [
                'html' => '<h5 class="mt-4">' . trans('Reseller System') . '</h5>',
            ])
            ->add(
                'enable_reseller_system',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('Enable Reseller System'))
                    ->helperText(trans('Allow customers to become resellers and earn commissions'))
                    ->value(get_ecommerce_setting('enable_reseller_system', true))
            )
            ->add(
                'default_reseller_commission_rate',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('Default Reseller Commission Rate (%)'))
                    ->helperText(trans('Default commission percentage for new resellers'))
                    ->value(get_ecommerce_setting('default_reseller_commission_rate', 5))
                    ->attributes(['min' => 0, 'max' => 100, 'step' => 0.1])
            )
            ->add(
                'reseller_min_payout',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('Minimum Reseller Payout Amount'))
                    ->helperText(trans('Minimum amount required before reseller can request payout'))
                    ->value(get_ecommerce_setting('reseller_min_payout', 50))
                    ->attributes(['min' => 0, 'step' => 0.01])
            )

            // Phone Verification Settings
            ->add('phone_section_title', 'html', [
                'html' => '<h5 class="mt-4">' . trans('Phone Verification') . '</h5>',
            ])
            ->add(
                'enable_phone_verification',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('Enable Phone Verification'))
                    ->helperText(trans('Require customers to verify their phone numbers'))
                    ->value(get_ecommerce_setting('enable_phone_verification', false))
            )
            ->add(
                'otp_expiry_minutes',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('OTP Expiry Time (minutes)'))
                    ->helperText(trans('How long OTP codes remain valid'))
                    ->value(get_ecommerce_setting('otp_expiry_minutes', 5))
                    ->attributes(['min' => 1, 'max' => 30])
            )

            // Buy Now Settings
            ->add('buy_now_section_title', 'html', [
                'html' => '<h5 class="mt-4">' . trans('Buy Now Features') . '</h5>',
            ])
            ->add(
                'buy_now_guest_checkout',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('Allow Guest Buy Now'))
                    ->helperText(trans('Allow guests to use Buy Now without creating an account'))
                    ->value(get_ecommerce_setting('buy_now_guest_checkout', true))
            )
            ->add(
                'buy_now_auto_approve_payment',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('Auto-approve Buy Now Orders'))
                    ->helperText(trans('Automatically approve orders placed via Buy Now'))
                    ->value(get_ecommerce_setting('buy_now_auto_approve_payment', false))
            );
    }
}
