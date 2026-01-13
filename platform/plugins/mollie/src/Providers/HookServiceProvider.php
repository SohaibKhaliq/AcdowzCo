<?php

namespace Botble\Mollie\Providers;

use Botble\Base\Facades\Html;
use Botble\Mollie\Forms\MolliePaymentMethodForm;
use Botble\Mollie\Services\Gateways\MolliePaymentService;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Facades\PaymentMethods;
use Botble\Payment\Supports\PaymentHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Mollie\Laravel\Facades\Mollie;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerMollieMethod'], 17, 2);

        $this->app->booted(function (): void {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithMollie'], 17, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 99);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['MOLLIE'] = MOLLIE_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 23, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == MOLLIE_PAYMENT_METHOD_NAME) {
                $value = 'Mollie';
            }

            return $value;
        }, 23, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == MOLLIE_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 23, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == MOLLIE_PAYMENT_METHOD_NAME) {
                $data = MolliePaymentService::class;
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == MOLLIE_PAYMENT_METHOD_NAME) {
                try {
                    $paymentService = (new MolliePaymentService());

                    $paymentDetail = $paymentService->getPaymentDetails($payment->charge_id);

                    if ($paymentDetail) {
                        $data .= view('plugins/mollie::detail', ['payment' => $paymentDetail])->render();
                    }
                } catch (Exception) {
                    return $data;
                }
            }

            return $data;
        }, 20, 2);
    }

    public function addPaymentSettings(?string $settings): string
    {
        return $settings . MolliePaymentMethodForm::create()->renderForm();
    }

    public function registerMollieMethod(?string $html, array $data): ?string
    {
        PaymentMethods::method(MOLLIE_PAYMENT_METHOD_NAME, [
            'html' => view('plugins/mollie::methods', $data)->render(),
        ]);

        return $html;
    }

    public function checkoutWithMollie(array $data, Request $request)
    {
        if ($data['type'] !== MOLLIE_PAYMENT_METHOD_NAME) {
            return $data;
        }

        $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

        try {
            $api = Mollie::api();

            $requestData = [
                'amount' => [
                    'currency' => $paymentData['currency'],
                    'value' => number_format((float) $paymentData['amount'], 2, '.', ''),
                ],
                'description' => $paymentData['description'],
                'redirectUrl' => PaymentHelper::getRedirectURL($paymentData['checkout_token']),
                'cancelUrl' => PaymentHelper::getCancelURL($paymentData['checkout_token']),
                'webhookUrl' => route('mollie.payment.webhook', $paymentData['checkout_token']),
                'metadata' => [
                    'order_id' => $paymentData['order_id'],
                    'customer_id' => $paymentData['customer_id'],
                    'customer_type' => $paymentData['customer_type'],
                ],
            ];

            do_action('payment_before_making_api_request', MOLLIE_PAYMENT_METHOD_NAME, $requestData);

            $response = $api->payments->create($requestData);

            do_action('payment_after_api_response', MOLLIE_PAYMENT_METHOD_NAME, $requestData, (array) $response);

            header('Location: ' . $response->getCheckoutUrl());
            exit;
        } catch (Exception $exception) {
            $data['error'] = true;
            $data['message'] = $exception->getMessage();
        }

        return $data;
    }
}
