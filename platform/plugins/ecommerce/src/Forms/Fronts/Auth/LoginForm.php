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
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Ecommerce\Http\Requests\LoginRequest;
use Botble\Ecommerce\Models\Customer;

class LoginForm extends AuthForm
{
    public static function formTitle(): string
    {
        return __('Customer login form');
    }

    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('customer.login.post'))
            ->setValidatorClass(LoginRequest::class)
            ->icon('ti ti-lock')
            ->heading(__('Login to your account'))
            ->description(__('Your personal data will be used to support your experience throughout this website, to manage access to your account.'))
            ->when(
                theme_option('login_background'),
                fn (AuthForm $form, string $background) => $form->banner($background)
            )
            ->when(EcommerceHelper::getLoginOption() === 'phone', function (LoginForm $form): void {
                $form->add(
                    'email',
                    PhoneNumberField::class,
                    TextFieldOption::make()
                        ->label(__('Phone'))
                        ->placeholder(__('Phone number'))
                        ->icon('ti ti-phone')
                        ->addAttribute('autocomplete', 'tel')
                );
            })
            ->when(EcommerceHelper::getLoginOption() === 'email', function (LoginForm $form): void {
                $form->add(
                    'email',
                    EmailField::class,
                    EmailFieldOption::make()
                        ->label(__('Email'))
                        ->placeholder(__('Email address'))
                        ->icon('ti ti-mail')
                );
            })
            ->when(EcommerceHelper::getLoginOption() === 'email_or_phone', function (LoginForm $form): void {
                $form->add(
                    'email',
                    EmailField::class,
                    EmailFieldOption::make()
                        ->label(__('Email or phone'))
                        ->placeholder(__('Email or Phone number'))
                        ->addAttribute('autocomplete', 'email')
                        ->icon('ti ti-user')
                );
            })
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password'))
                    ->placeholder(__('Password'))
                    ->icon('ti ti-lock')
            )
            ->add('openRow', HtmlField::class, [
                'html' => '<div class="row g-0 mb-3">',
            ])
            ->add(
                'remember',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Remember me'))
                    ->wrapperAttributes(['class' => 'col-6'])
            )
            ->add(
                'forgot_password',
                HtmlField::class,
                [
                    'html' => Html::link(route('customer.password.reset'), __('Forgot password?'), attributes: ['class' => 'text-decoration-underline']),
                    'wrapper' => [
                        'class' => 'col-6 text-end',
                    ],
                ]
            )
            ->add('closeRow', HtmlField::class, [
                'html' => '</div>',
            ])
            ->add('otp_toggle', HtmlField::class, [
                'html' => '<div class="mb-3"><a href="javascript:void(0)" id="toggle-otp-login" class="text-primary">' . __('Login with OTP') . '</a></div>',
            ])
            ->add('otp_fields', HtmlField::class, [
                'html' => '
                    <div id="otp-login-wrapper" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">' . __('Phone Number') . '</label>
                            <div class="input-group">
                                <input type="text" id="otp-phone" class="form-control" placeholder="' . __('Enter phone number') . '">
                                <button type="button" class="btn btn-secondary" id="send-otp-btn">' . __('Send OTP') . '</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">' . __('Enter 6-digit OTP') . '</label>
                            <input type="text" id="otp-code" class="form-control" maxlength="6" placeholder="000000">
                        </div>
                        <button type="button" class="btn btn-primary w-100 mb-3" id="verify-otp-btn">' . __('Verify & Login') . '</button>
                        <div class="mb-3"><a href="javascript:void(0)" id="toggle-password-login" class="text-primary">' . __('Login with Password') . '</a></div>
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const otpWrapper = document.getElementById("otp-login-wrapper");
                            const passwordForm = document.querySelector(".auth-form form");
                            const toggleOtp = document.getElementById("toggle-otp-login");
                            const togglePassword = document.getElementById("toggle-password-login");
                            const sendOtpBtn = document.getElementById("send-otp-btn");
                            const verifyOtpBtn = document.getElementById("verify-otp-btn");

                            toggleOtp.addEventListener("click", function() {
                                passwordForm.style.display = "none";
                                otpWrapper.style.display = "block";
                            });

                            togglePassword.addEventListener("click", function() {
                                passwordForm.style.display = "block";
                                otpWrapper.style.display = "none";
                            });

                            sendOtpBtn.addEventListener("click", function() {
                                const phone = document.getElementById("otp-phone").value;
                                if (!phone) { alert("' . __('Please enter phone number') . '"); return; }
                                sendOtpBtn.disabled = true;
                                fetch("' . route('customer.otp.send') . '", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').content
                                    },
                                    body: JSON.stringify({ phone: phone })
                                }).then(res => res.json()).then(data => {
                                    alert(data.message);
                                    if (data.error) sendOtpBtn.disabled = false;
                                });
                            });

                            verifyOtpBtn.addEventListener("click", function() {
                                const phone = document.getElementById("otp-phone").value;
                                const otp = document.getElementById("otp-code").value;
                                fetch("' . route('customer.otp.verify') . '", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').content
                                    },
                                    body: JSON.stringify({ phone: phone, otp: otp })
                                }).then(res => res.json()).then(data => {
                                    if (data.error) {
                                        alert(data.message);
                                    } else {
                                        window.location.href = data.data.next_url;
                                    }
                                });
                            });
                        });
                    </script>
                ',
            ])
            ->submitButton(__('Login'), 'ti ti-arrow-narrow-right')
            ->add(
                'register',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->view('plugins/ecommerce::customers.includes.register-link')
            )
            ->add('filters', HtmlField::class, [
                'html' => apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, Customer::class),
            ]);
    }
}
