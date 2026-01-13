<?php

namespace Botble\Ecommerce\Forms\Fronts;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\SaveCheckoutInformationRequest;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Facades\PaymentMethods;
use Botble\Theme\Facades\Theme;
use Botble\Theme\FormFront;
use Closure;
use Detection\MobileDetect;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Throwable;

class CheckoutForm extends FormFront
{
    public function setup(): void
    {
        $model = $this->getModel();
        $token = $model['token'];

        $this
            ->contentOnly()
            ->setUrl(route('public.checkout.process', $token))
            ->setValidatorClass(SaveCheckoutInformationRequest::class)
            ->formClass('checkout-form payment-checkout-form')
            ->setFormOptions([
                'id' => 'checkout-form',
                'data-update-url' => route('public.ajax.checkout.update'),
            ])
            ->add('checkout-token', 'hidden', TextFieldOption::make()->value($token)->maxLength(0))
            ->addWrapper(
                'main_checkout_product_info',
                '<div class="row" id="main-checkout-product-info">',
                '</div>',
                function (CheckoutForm $form) use ($token, $model): void {
                    $cartItemHtml = HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.amount', $model));
                    $discountFormHtml = HtmlFieldOption::make()->content(view(EcommerceHelper::viewPath('discounts.partials.form'), ['discounts' => $model['discounts']]));

                    try {
                        $mobileDetect = new MobileDetect();

                        $isMobile = $mobileDetect->isMobile();
                    } catch (Throwable) {
                        $isMobile = false;
                    }

                    $form
                        ->when(! $isMobile, function (CheckoutForm $form) use ($model, $discountFormHtml, $cartItemHtml): void {
                            $form->addWrapper(
                                'right_column_wrapper',
                                '<div class="col-lg-5 col-md-6 order-2 checkout-order-info">',
                                '</div>',
                                function (CheckoutForm $form) use ($discountFormHtml, $cartItemHtml, $model): void {
                                    $form
                                        ->addWrapper(
                                            'right_column_cart_item_wrapper',
                                            '<div class="my-3 bg-light"><div class="position-relative p-3 cart-item-wrapper">',
                                            '</div></div>',
                                            fn (CheckoutForm $form) => $form->add(
                                                'right_column_cart_item',
                                                HtmlField::class,
                                                $cartItemHtml
                                            )
                                        )
                                        ->addWrapper(
                                            'right_column_discount_wrapper',
                                            '<div class="mt-3 mb-5">',
                                            '</div>',
                                            fn (CheckoutForm $form) => $form->add(
                                                'right_column_discount',
                                                HtmlField::class,
                                                $discountFormHtml,
                                            )
                                        );
                                }
                            );
                        })
                        ->addWrapper(
                            'left_column_wrapper',
                            $isMobile ? '<div class="form-checkout col-lg-7">' : '<div class="form-checkout col-lg-7 col-md-6">',
                            '</div>',
                            function (CheckoutForm $form) use ($isMobile, $discountFormHtml, $cartItemHtml, $token, $model): void {
                                $form
                                    ->addWrapper(
                                        'left_column_logo_wrapper',
                                        '<div>',
                                        '</div>',
                                        fn (CheckoutForm $form) => $form->add(
                                            'left_column_logo',
                                            HtmlField::class,
                                            HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.logo'))
                                        )
                                    )
                                    ->add(
                                        'filters_ecommerce_checkout_form_before',
                                        HtmlField::class,
                                        HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_before', null, $model['products']))
                                    )
                                    ->when($model['isShowAddressForm'], function (CheckoutForm $form) use ($model, $token): void {
                                        $form
                                            ->addWrapper(
                                                'shipping_information_wrapper',
                                                '<div class="mb-4">',
                                                '</div>',
                                                function (CheckoutForm $form) use ($model, $token): void {
                                                    $form
                                                        ->add(
                                                            'shipping_information_title',
                                                            HtmlField::class,
                                                            HtmlFieldOption::make()->content('<h5 class="checkout-shipping-information-title">' . __('Shipping information') . '</h5>')
                                                        )
                                                        ->add(
                                                            'save-shipping-information-url',
                                                            'hidden',
                                                            TextFieldOption::make()->attributes(['id' => 'save-shipping-information-url'])->value(route('public.checkout.save-information', $token)),
                                                        )
                                                        ->add(
                                                            'shipping_address_form',
                                                            HtmlField::class,
                                                            HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.address-form', $model))
                                                        );
                                                },
                                            )
                                            ->add(
                                                'filters_ecommerce_checkout_form_after_shipping_address_form',
                                                HtmlField::class,
                                                HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_after_shipping_address_form', null, $model['products']))
                                            );
                                    })
                                    ->when(EcommerceHelper::isBillingAddressEnabled(), function (CheckoutForm $form) use ($model): void {
                                        $form
                                            ->addWrapper(
                                                'billing_information_wrapper',
                                                '<div class="mb-4">',
                                                '</div>',
                                                function (CheckoutForm $form) use ($model): void {
                                                    $form
                                                        ->add(
                                                            'billing_information_title',
                                                            HtmlField::class,
                                                            HtmlFieldOption::make()->content('<h5 class="checkout-billing-information-title">' . __('Billing information') . '</h5>')
                                                        )
                                                        ->add(
                                                            'billing_address_form',
                                                            HtmlField::class,
                                                            HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.billing-address-form', $model))
                                                        );
                                                }
                                            )
                                            ->add(
                                                'filters_ecommerce_checkout_form_after_billing_address_form',
                                                HtmlField::class,
                                                HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_after_billing_address_form', null, $model['products']))
                                            );
                                    })
                                    ->when(
                                        apply_filters('ecommerce_checkout_show_shipping_section', ! is_plugin_active('marketplace'))
                                        && Arr::get($model, 'sessionCheckoutData.is_available_shipping', true)
                                        && (! (bool) get_ecommerce_setting('disable_shipping_options', false)),
                                        function (CheckoutForm $form) use ($model): void {
                                            $form
                                                ->addWrapper(
                                                    'shipping_method_wrapper',
                                                    '<div class="shipping-method-wrapper mb-4">',
                                                    '</div>',
                                                    function (CheckoutForm $form) use ($model): void {
                                                        $form
                                                            ->add(
                                                                'shipping_method_title',
                                                                HtmlField::class,
                                                                HtmlFieldOption::make()->content('<h5 class="checkout-payment-title">' . __('Shipping method') . '</h5>')
                                                            )
                                                            ->add(
                                                                'shipping_method_loading',
                                                                HtmlField::class,
                                                                HtmlFieldOption::make()->content('<div class="shipping-info-loading loading-spinner" style="display: none;"></div>')
                                                            )
                                                            ->addWrapper(
                                                                'shipping_methods_area_wrapper',
                                                                '<div data-bb-toggle="checkout-shipping-methods-area">',
                                                                '</div>',
                                                                function (CheckoutForm $form) use ($model): void {
                                                                    $form->add(
                                                                        'shipping_methods',
                                                                        HtmlField::class,
                                                                        HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.shipping-methods', $model))
                                                                    );
                                                                }
                                                            );
                                                    }
                                                )
                                                ->add(
                                                    'filters_ecommerce_checkout_form_after_shipping_method_form',
                                                    HtmlField::class,
                                                    HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_after_shipping_address_form', null, $model['products']))
                                                );
                                        }
                                    )
                                    ->when($isMobile, function (CheckoutForm $form) use ($discountFormHtml, $cartItemHtml): void {
                                        $form
                                            ->addWrapper(
                                                'mobile_cart_item_wrapper',
                                                '<div class="my-3 bg-light"><div class="position-relative p-3 cart-item-wrapper">',
                                                '</div></div>',
                                                fn (CheckoutForm $form) => $form->add(
                                                    'mobile_cart_item',
                                                    HtmlField::class,
                                                    $cartItemHtml,
                                                )
                                            )
                                            ->addWrapper(
                                                'mobile_discount_wrapper',
                                                '<div class="mt-3 mb-5">',
                                                '</div>',
                                                fn (CheckoutForm $form) => $form->add(
                                                    'mobile_discount',
                                                    HtmlField::class,
                                                    $discountFormHtml,
                                                )
                                            );
                                    })
                                    ->add(
                                        'filters_ecommerce_checkout_form_before_payment_form',
                                        HtmlField::class,
                                        HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_before_payment_form', null, $model['products']))
                                    )
                                    ->add(
                                        'amount',
                                        'hidden',
                                        TextFieldOption::make()->value(format_price($model['orderAmount'], null, true)),
                                    )
                                    ->addWrapper(
                                        'payment_methods_wrapper',
                                        '<div data-bb-toggle="checkout-payment-methods-area">',
                                        '</div>',
                                        function (CheckoutForm $form) use ($model): void {
                                            $filteredModel = $form->filterPaymentMethods($model);

                                            $form->add(
                                                'payment_methods',
                                                HtmlField::class,
                                                HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.payment-methods', $filteredModel))
                                            );
                                        }
                                    )
                                    ->add(
                                        'filters_ecommerce_checkout_form_after_payment_form',
                                        HtmlField::class,
                                        HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_after_payment_form', null, $model['products']))
                                    )
                                    ->add(
                                        'description',
                                        TextareaField::class,
                                        TextareaFieldOption::make()
                                            ->wrapperAttributes(['class' => 'form-group mb-3'])
                                            ->rows(3)
                                            ->label(__('Order notes'))
                                            ->placeholder(__('Notes about your order, e.g. special notes for delivery.'))
                                    )
                                    ->when(EcommerceHelper::isDisplayTaxFieldsAtCheckoutPage(), function (CheckoutForm $form) use ($model): void {
                                        $form->add(
                                            'tax_information',
                                            HtmlField::class,
                                            HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.tax-information', $model))
                                        );
                                    })
                                    ->add(
                                        'filters_ecommerce_checkout_form_after_tax_information_form',
                                        HtmlField::class,
                                        HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_after_tax_information_form', null, $model['products']))
                                    )
                                    ->when(Theme::termAndPrivacyPolicyUrl() && get_ecommerce_setting('show_terms_and_policy_checkbox', true), function (CheckoutForm $form): void {
                                        $form->add(
                                            'agree_terms_and_policy',
                                            CheckboxField::class,
                                            CheckboxFieldOption::make()
                                                ->label(BaseHelper::clean(__(
                                                    'I agree to the :link',
                                                    ['link' => Html::link(Theme::termAndPrivacyPolicyUrl(), __('Terms and Privacy Policy'), attributes: ['class' => 'text-decoration-underline', 'target' => '_blank'])]
                                                )))
                                                ->checked(get_ecommerce_setting('terms_and_policy_checkbox_checked_by_default', false)),
                                        );
                                    })
                                    ->when(get_ecommerce_setting('checkout_acceptance_message_enabled', false), function (CheckoutForm $form): void {
                                        $form->add(
                                            'checkout_acceptance_message',
                                            HtmlField::class,
                                            HtmlFieldOption::make()->content(
                                                '<div class="alert alert-info mb-3" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.75rem 1rem; color: #6c757d; font-size: 14px; line-height: 1.5;">' .
                                                __('By placing an order, you agree to our Terms of Service and acknowledge that you have read our Privacy Policy. Your payment will be processed securely according to our payment provider\'s privacy policy.') .
                                                '</div>'
                                            )
                                        );
                                    })
                                    ->add(
                                        'filters_ecommerce_checkout_form_after',
                                        HtmlField::class,
                                        HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_after', null, $model['products']))
                                    )
                                    ->addWrapper(
                                        'footer_actions_wrapper',
                                        '<div class="row align-items-center mb-5">',
                                        '</div>',
                                        function (CheckoutForm $form) use ($model): void {
                                            $form
                                                ->addWrapper(
                                                    'footer_actions_left_wrapper',
                                                    '<div class="order-2 order-md-1 col-md-6 text-center text-md-start mb-4 mb-md-0">',
                                                    '</div>',
                                                    function (CheckoutForm $form) use ($model): void {
                                                        $form
                                                            ->add(
                                                                'footer_actions_back_to_cart',
                                                                HtmlField::class,
                                                                HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.back-to-cart'))
                                                            )
                                                            ->add(
                                                                'filters_ecommerce_checkout_form_after_back_to_cart_link',
                                                                HtmlField::class,
                                                                HtmlFieldOption::make()->content(apply_filters('ecommerce_checkout_form_after_back_to_cart_link', null, $model['products']))
                                                            );
                                                    }
                                                )
                                                ->addWrapper(
                                                    'footer_actions_right_wrapper',
                                                    '<div class="order-1 order-md-2 col-md-6">',
                                                    '</div>',
                                                    function (CheckoutForm $form): void {
                                                        $form->add(
                                                            'footer_actions_checkout',
                                                            HtmlField::class,
                                                            HtmlFieldOption::make()->content(view('plugins/ecommerce::orders.partials.checkout-button'))
                                                        );
                                                    }
                                                )
                                                ->setFormEndKey('filters_ecommerce_checkout_form_after');
                                        }
                                    );
                            }
                        );
                }
            );
    }

    protected function addWrapper(string $name, string $open, string $close, Closure $callback): static
    {
        $this->add(
            "open_$name",
            HtmlField::class,
            HtmlFieldOption::make()->content($open)
        );

        $callback($this);

        $this->add(
            "close_$name",
            HtmlField::class,
            HtmlFieldOption::make()->content($close)
        );

        return $this;
    }

    protected function cartContainsOnlyDigitalProducts(Collection $products): bool
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            return false;
        }

        if ($products->isEmpty()) {
            return false;
        }

        $digitalProductsCount = EcommerceHelper::countDigitalProducts($products);

        return $digitalProductsCount > 0 && $digitalProductsCount === $products->count();
    }

    protected function filterPaymentMethods(array $model): array
    {
        if ($this->cartContainsOnlyDigitalProducts($model['products'])) {
            PaymentMethods::excludeMethod(PaymentMethodEnum::COD);
        }

        return $model;
    }
}
