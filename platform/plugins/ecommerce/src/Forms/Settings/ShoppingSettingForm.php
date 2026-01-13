<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\ShoppingSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ShoppingSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.shopping.name'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.shopping.description'))
            ->setValidatorClass(ShoppingSettingRequest::class)
            ->add(
                'shopping_cart_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_cart'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_cart_helper'))
                    ->value($shoppingCartEnabled = EcommerceHelper::isCartEnabled())
            )
            ->addOpenCollapsible('shopping_cart_enabled', '1', $shoppingCartEnabled == '1')
            ->add(
                'cart_destroy_on_logout',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.cart_destroy_on_logout'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.cart_destroy_on_logout_helper'))
                    ->value(get_ecommerce_setting('cart_destroy_on_logout', false))
            )
            ->add(
                'order_tracking_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_order_tracking'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_order_tracking_helper', ['url' => route('public.orders.tracking')]))
                    ->value($orderTrackingEnabled = EcommerceHelper::isOrderTrackingEnabled())
            )
            ->addOpenCollapsible('order_tracking_enabled', '1', $orderTrackingEnabled == '1')
            ->add(
                'order_tracking_method',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.order_tracking_method'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.order_tracking_method_helper'))
                    ->choices([
                        'email' => trans('plugins/ecommerce::setting.shopping.form.order_tracking_method_email'),
                        'phone' => trans('plugins/ecommerce::setting.shopping.form.order_tracking_method_phone'),
                    ])
                    ->selected(get_ecommerce_setting('order_tracking_method', 'email'))
            )
            ->addCloseCollapsible('order_tracking_enabled', '1')
            ->add(
                'payment_proof_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_payment_proof'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_payment_proof_helper'))
                    ->value($paymentProofEnabled = EcommerceHelper::isPaymentProofEnabled())
            )
            ->addOpenCollapsible('payment_proof_enabled', '1', $paymentProofEnabled == '1')
            ->add(
                'guest_payment_proof_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_guest_payment_proof'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_guest_payment_proof_helper'))
                    ->value(get_ecommerce_setting('guest_payment_proof_enabled', true))
            )
            ->addCloseCollapsible('payment_proof_enabled', '1')
            ->add(
                'enable_quick_buy_button',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_quick_buy_button'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_quick_buy_button_helper'))
                    ->value(EcommerceHelper::isQuickBuyButtonEnabled())
            )
            ->add(
                'quick_buy_target_page',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.quick_buy_target'))
                    ->choices([
                        'checkout' => trans('plugins/ecommerce::setting.shopping.form.checkout_page'),
                        'cart' => trans('plugins/ecommerce::setting.shopping.form.cart_page'),
                    ])
                    ->selected(EcommerceHelper::getQuickBuyButtonTarget())
            )
            ->add(
                'order_auto_confirmed',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_order_auto_confirmed'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_order_auto_confirmed_helper'))
                    ->value(EcommerceHelper::isOrderAutoConfirmedEnabled())
            )
            ->addCloseCollapsible('shopping_cart_enabled', '1')
            ->add(
                'hide_product_price',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.hide_product_price'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.hide_product_price_helper'))
                    ->value(EcommerceHelper::hideProductPrice())
            )
            ->add(
                'wishlist_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_wishlist'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_wishlist_helper'))
                    ->value($wishlistEnabled = EcommerceHelper::isWishlistEnabled())
            )
            ->addOpenCollapsible('wishlist_enabled', '1', $wishlistEnabled == '1')
            ->add(
                'wishlist_sharing',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_wishlist_sharing'))
                    ->collapsible('wishlist_enabled', true, EcommerceHelper::isWishlistEnabled())
                    ->value(EcommerceHelper::isWishlistSharingEnabled())
            )
            ->add(
                'shared_wishlist_lifetime',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.shared_wishlist_lifetime'))
                    ->value(EcommerceHelper::getSharedWishlistLifetime())
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.shared_wishlist_lifetime_helper'))
            )
            ->addCloseCollapsible('wishlist_enabled', '1')
            ->add(
                'compare_enabled',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.shopping.form.enable_compare'))
                    ->helperText(trans('plugins/ecommerce::setting.shopping.form.enable_compare_helper'))
                    ->value(EcommerceHelper::isCompareEnabled())
            );
    }
}
