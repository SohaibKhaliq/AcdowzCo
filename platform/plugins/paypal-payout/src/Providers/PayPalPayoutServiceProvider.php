<?php

namespace Botble\PayPalPayout\Providers;

use Botble\AffiliatePro\Events\WithdrawalRequestedEvent as AffiliateWithdrawalRequestedEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Marketplace\Enums\PayoutPaymentMethodsEnum;
use Botble\Marketplace\Models\Withdrawal;
use Botble\PayPalPayout\Listeners\AffiliatePayPalPayoutListener;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class PayPalPayoutServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        $this->setNamespace('plugins/paypal-payout')
            ->loadAndPublishViews()
            ->publishAssets()
            ->loadRoutes();

        // Register event listeners
        if (is_plugin_active('affiliate-pro')) {
            Event::listen(AffiliateWithdrawalRequestedEvent::class, AffiliatePayPalPayoutListener::class);
        }

        $this->app->booted(function (): void {
            add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
                if (
                    is_in_admin(true) &&
                    Auth::check() &&
                    $data->getKey() &&
                    (
                        (is_plugin_active('marketplace') && $data instanceof Withdrawal && $data->payment_channel == PayoutPaymentMethodsEnum::PAYPAL) ||
                        (is_plugin_active('affiliate-pro') && $data instanceof \Botble\AffiliatePro\Models\Withdrawal && $data->payment_channel == \Botble\AffiliatePro\Enums\PayoutPaymentMethodsEnum::PAYPAL)
                    )
                ) {
                    Assets::addScriptsDirectly('vendor/core/plugins/paypal-payout/js/paypal-payout.js');

                    $form
                        ->add('payout-form', 'html', [
                            'html' => view('plugins/paypal-payout::payout-form', compact('data'))->render(),
                            'label' => __('PayPal automatically payout'),
                        ]);
                }

                return $form;
            }, 123, 2);
        });
    }
}
