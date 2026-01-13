<?php

namespace Botble\Ecommerce\Forms\Fronts\Auth;

use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\Fields\PhoneNumberField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Ecommerce\Http\Requests\RegisterRequest;
use Botble\Ecommerce\Models\Customer;
use Botble\Theme\Facades\Theme;

class RegisterForm extends AuthForm
{
    public static function formTitle(): string
    {
        return __('Customer register form');
    }

    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('customer.register.post'))
            ->setValidatorClass(RegisterRequest::class)
            ->icon('ti ti-user-plus')
            ->heading(__('Register an account'))
            ->description(__('Your personal data will be used to support your experience throughout this website, to manage access to your account.'))
            ->when(
                theme_option('register_background'),
                fn (AuthForm $form, string $background) => $form->banner($background)
            )
            ->add(
                'name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Full name'))
                    ->placeholder(__('Your full name'))
                    ->required()
                    ->icon('ti ti-user')
            )
            ->when(! EcommerceHelper::isLoginUsingPhone() || get_ecommerce_setting('keep_email_field_in_registration_form', true), function (FormAbstract $form): void {
                $form
                    ->add(
                        'email',
                        EmailField::class,
                        EmailFieldOption::make()
                            ->label(__('Email'))
                            ->when(EcommerceHelper::isLoginUsingPhone(), function (EmailFieldOption $fieldOption): void {
                                $fieldOption->label(__('Email (optional)'));
                            }, function (EmailFieldOption $fieldOption): void {
                                $fieldOption->required();
                            })
                            ->placeholder(__('Your email'))
                            ->icon('ti ti-mail')
                            ->addAttribute('autocomplete', 'email')
                    );
            })
            ->when(get_ecommerce_setting('enabled_phone_field_in_registration_form', true), static function (FormAbstract $form): void {
                $form
                    ->add(
                        'phone',
                        PhoneNumberField::class,
                        TextFieldOption::make()
                            ->label(__('Phone (optional)'))
                            ->when(EcommerceHelper::isLoginUsingPhone() || get_ecommerce_setting('make_customer_phone_number_required', false), static function (TextFieldOption $fieldOption): void {
                                $fieldOption
                                    ->required()
                                    ->label(__('Phone'));
                            })
                            ->placeholder(__('Phone number'))
                            ->icon('ti ti-phone')
                            ->addAttribute('autocomplete', 'tel')
                    );
            })
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password'))
                    ->placeholder(__('Password'))
                    ->icon('ti ti-lock')
                    ->required()
            )
            ->add(
                'password_confirmation',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password confirmation'))
                    ->placeholder(__('Password confirmation'))
                    ->icon('ti ti-lock')
                    ->required()
            )
            ->add(
                'agree_terms_and_policy',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->when(
                        $privacyPolicyUrl = Theme::termAndPrivacyPolicyUrl(),
                        function (CheckboxFieldOption $fieldOption, string $url): void {
                            $fieldOption->label(__('I agree to the :link', ['link' => Html::link($url, __('Terms and Privacy Policy'), attributes: ['class' => 'text-decoration-underline', 'target' => '_blank'])]));
                        }
                    )
                    ->when(! $privacyPolicyUrl, function (CheckboxFieldOption $fieldOption): void {
                        $fieldOption->label(__('I agree to the Terms and Privacy Policy'));
                    })
            )
            ->submitButton(__('Register'), 'ti ti-arrow-narrow-right')
            ->add(
                'login',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->view('plugins/ecommerce::customers.includes.login-link')
            )
            ->add('filters', HtmlField::class, [
                'html' => apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, Customer::class),
            ]);
    }
}
