<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Ecommerce\Http\Requests\Settings\WebhookSettingRequest;
use Botble\Setting\Forms\SettingForm;

class WebhookSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.webhook.webhook_setting'))
            ->setSectionDescription(trans('plugins/ecommerce::setting.webhook.webhook_setting_description'))
            ->setValidatorClass(WebhookSettingRequest::class)
            ->add(
                'order_placed_webhook_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.webhook.form.order_placed_webhook_url'))
                    ->value(get_ecommerce_setting('order_placed_webhook_url'))
                    ->placeholder(trans('plugins/ecommerce::setting.webhook.form.order_placed_webhook_url_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.webhook.form.order_placed_webhook_url_helper'))
            )
            ->add(
                'order_placed_sample_data',
                HtmlField::class,
                [
                    'html' => $this->getSampleDataHtml(
                        'View Sample Data - Order Placed',
                        $this->getOrderPlacedSampleData()
                    ) . $this->getTestButtonHtml('order_placed', 'order_placed_webhook_url'),
                ]
            )
            ->add(
                'order_updated_webhook_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.webhook.form.order_updated_webhook_url'))
                    ->value(get_ecommerce_setting('order_updated_webhook_url'))
                    ->placeholder(trans('plugins/ecommerce::setting.webhook.form.order_updated_webhook_url_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.webhook.form.order_updated_webhook_url_helper'))
            )
            ->add(
                'order_updated_sample_data',
                HtmlField::class,
                [
                    'html' => $this->getSampleDataHtml(
                        'View Sample Data - Order Updated',
                        $this->getOrderUpdatedSampleData()
                    ) . $this->getTestButtonHtml('order_updated', 'order_updated_webhook_url'),
                ]
            )
            ->add(
                'shipping_status_updated_webhook_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.webhook.form.shipping_status_updated_webhook_url'))
                    ->value(get_ecommerce_setting('shipping_status_updated_webhook_url'))
                    ->placeholder(trans('plugins/ecommerce::setting.webhook.form.shipping_status_updated_webhook_url_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.webhook.form.shipping_status_updated_webhook_url_helper'))
            )
            ->add(
                'shipping_status_updated_sample_data',
                HtmlField::class,
                [
                    'html' => $this->getSampleDataHtml(
                        'View Sample Data - Shipping Status Updated',
                        $this->getShippingStatusUpdatedSampleData()
                    ) . $this->getTestButtonHtml('shipping_status_updated', 'shipping_status_updated_webhook_url'),
                ]
            )
            ->add(
                'order_completed_webhook_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.webhook.form.order_completed_webhook_url'))
                    ->value(get_ecommerce_setting('order_completed_webhook_url'))
                    ->placeholder(trans('plugins/ecommerce::setting.webhook.form.order_completed_webhook_url_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.webhook.form.order_completed_webhook_url_helper'))
            )
            ->add(
                'order_completed_sample_data',
                HtmlField::class,
                [
                    'html' => $this->getSampleDataHtml(
                        'View Sample Data - Order Completed',
                        $this->getOrderCompletedSampleData()
                    ) . $this->getTestButtonHtml('order_completed', 'order_completed_webhook_url'),
                ]
            )
            ->add(
                'order_cancelled_webhook_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.webhook.form.order_cancelled_webhook_url'))
                    ->value(get_ecommerce_setting('order_cancelled_webhook_url'))
                    ->placeholder(trans('plugins/ecommerce::setting.webhook.form.order_cancelled_webhook_url_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.webhook.form.order_cancelled_webhook_url_helper'))
            )
            ->add(
                'order_cancelled_sample_data',
                HtmlField::class,
                [
                    'html' => $this->getSampleDataHtml(
                        'View Sample Data - Order Cancelled',
                        $this->getOrderCancelledSampleData()
                    ) . $this->getTestButtonHtml('order_cancelled', 'order_cancelled_webhook_url'),
                ]
            )
            ->add(
                'payment_status_updated_webhook_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.webhook.form.payment_status_updated_webhook_url'))
                    ->value(get_ecommerce_setting('payment_status_updated_webhook_url'))
                    ->placeholder(trans('plugins/ecommerce::setting.webhook.form.payment_status_updated_webhook_url_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.webhook.form.payment_status_updated_webhook_url_helper'))
            )
            ->add(
                'payment_status_updated_sample_data',
                HtmlField::class,
                [
                    'html' => $this->getSampleDataHtml(
                        'View Sample Data - Payment Status Updated',
                        $this->getPaymentStatusUpdatedSampleData()
                    ) . $this->getTestButtonHtml('payment_status_updated', 'payment_status_updated_webhook_url'),
                ]
            )
            ->add(
                'abandoned_cart_webhook_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.webhook.form.abandoned_cart_webhook_url'))
                    ->value(get_ecommerce_setting('abandoned_cart_webhook_url'))
                    ->placeholder(trans('plugins/ecommerce::setting.webhook.form.abandoned_cart_webhook_url_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.webhook.form.abandoned_cart_webhook_url_helper'))
            )
            ->add(
                'abandoned_cart_sample_data',
                HtmlField::class,
                [
                    'html' => $this->getSampleDataHtml(
                        'View Sample Data - Abandoned Cart',
                        $this->getAbandonedCartSampleData()
                    ) . $this->getTestButtonHtml('abandoned_cart', 'abandoned_cart_webhook_url'),
                ]
            )
            ->add(
                'webhook_test_script',
                HtmlField::class,
                [
                    'html' => $this->getWebhookTestScript(),
                ]
            );
    }

    protected function getSampleDataHtml(string $title, array $data): string
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return <<<HTML
            <div class="mb-3">
                <details class="border rounded p-3">
                    <summary class="cursor-pointer text-primary fw-bold">{$title}</summary>
                    <pre class="mt-3 p-3 rounded" style="max-height: 400px; overflow-y: auto;"><code>{$json}</code></pre>
                </details>
            </div>
        HTML;
    }

    protected function getTestButtonHtml(string $webhookType, string $urlFieldId): string
    {
        $testUrl = route('ecommerce.settings.webhook.test');
        $buttonText = trans('plugins/ecommerce::setting.webhook.test_button');
        $icon = BaseHelper::renderIcon('ti ti-send-2');

        return <<<HTML
            <div class="mb-3">
                <button type="button"
                        class="btn btn-sm btn-primary test-webhook-btn"
                        data-webhook-type="{$webhookType}"
                        data-url-field="{$urlFieldId}"
                        data-test-url="{$testUrl}">
                    {$icon} {$buttonText}
                </button>
                <div class="webhook-test-result mt-2" id="test-result-{$webhookType}" style="display: none;"></div>
            </div>
        HTML;
    }

    protected function getOrderPlacedSampleData(): array
    {
        return [
            'id' => 12345,
            'status' => [
                'value' => 'pending',
                'text' => 'Pending',
            ],
            'shipping_status' => [
                'value' => 'not_shipped',
                'text' => 'Not Shipped',
            ],
            'payment_method' => [
                'value' => 'stripe',
                'text' => 'Stripe',
            ],
            'payment_status' => [
                'value' => 'pending',
                'text' => 'Pending',
            ],
            'customer' => [
                'id' => 1,
                'name' => 'John Doe',
            ],
            'sub_total' => 99.99,
            'tax_amount' => 9.99,
            'shipping_method' => 'standard',
            'shipping_option' => 'Standard Shipping (5-7 days)',
            'shipping_amount' => 5.00,
            'amount' => 114.98,
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'discount_description' => '10% off discount',
            'note' => 'Please deliver to the back door',
            'is_confirmed' => true,
        ];
    }

    protected function getOrderUpdatedSampleData(): array
    {
        return [
            'id' => 12345,
            'status' => [
                'value' => 'processing',
                'text' => 'Processing',
            ],
            'shipping_status' => [
                'value' => 'arranging_shipment',
                'text' => 'Arranging Shipment',
            ],
            'payment_method' => [
                'value' => 'stripe',
                'text' => 'Stripe',
            ],
            'payment_status' => [
                'value' => 'paid',
                'text' => 'Paid',
            ],
            'customer' => [
                'id' => 1,
                'name' => 'John Doe',
            ],
            'sub_total' => 99.99,
            'tax_amount' => 9.99,
            'shipping_method' => 'standard',
            'shipping_option' => 'Standard Shipping (5-7 days)',
            'shipping_amount' => 5.00,
            'amount' => 114.98,
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'discount_description' => '10% off discount',
            'note' => 'Please deliver to the back door',
            'is_confirmed' => true,
        ];
    }

    protected function getShippingStatusUpdatedSampleData(): array
    {
        return [
            'id' => 12345,
            'status' => [
                'value' => 'processing',
                'text' => 'Processing',
            ],
            'shipping_status' => [
                'value' => 'delivered',
                'text' => 'Delivered',
            ],
            'previous_shipping_status' => [
                'value' => 'shipped',
                'text' => 'Shipped',
            ],
            'payment_method' => [
                'value' => 'stripe',
                'text' => 'Stripe',
            ],
            'payment_status' => [
                'value' => 'paid',
                'text' => 'Paid',
            ],
            'customer' => [
                'id' => 1,
                'name' => 'John Doe',
            ],
            'sub_total' => 99.99,
            'tax_amount' => 9.99,
            'shipping_method' => 'standard',
            'shipping_option' => 'Standard Shipping (5-7 days)',
            'shipping_amount' => 5.00,
            'amount' => 114.98,
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'discount_description' => '10% off discount',
            'note' => 'Please deliver to the back door',
            'is_confirmed' => true,
        ];
    }

    protected function getOrderCompletedSampleData(): array
    {
        return [
            'id' => 12345,
            'status' => [
                'value' => 'completed',
                'text' => 'Completed',
            ],
            'shipping_status' => [
                'value' => 'delivered',
                'text' => 'Delivered',
            ],
            'payment_method' => [
                'value' => 'stripe',
                'text' => 'Stripe',
            ],
            'payment_status' => [
                'value' => 'paid',
                'text' => 'Paid',
            ],
            'customer' => [
                'id' => 1,
                'name' => 'John Doe',
            ],
            'sub_total' => 99.99,
            'tax_amount' => 9.99,
            'shipping_method' => 'standard',
            'shipping_option' => 'Standard Shipping (5-7 days)',
            'shipping_amount' => 5.00,
            'amount' => 114.98,
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'discount_description' => '10% off discount',
            'note' => 'Please deliver to the back door',
            'is_confirmed' => true,
        ];
    }

    protected function getOrderCancelledSampleData(): array
    {
        return [
            'id' => 12345,
            'status' => [
                'value' => 'cancelled',
                'text' => 'Cancelled',
            ],
            'shipping_status' => [
                'value' => 'cancelled',
                'text' => 'Cancelled',
            ],
            'payment_method' => [
                'value' => 'stripe',
                'text' => 'Stripe',
            ],
            'payment_status' => [
                'value' => 'refunded',
                'text' => 'Refunded',
            ],
            'customer' => [
                'id' => 1,
                'name' => 'John Doe',
            ],
            'sub_total' => 99.99,
            'tax_amount' => 9.99,
            'shipping_method' => 'standard',
            'shipping_option' => 'Standard Shipping (5-7 days)',
            'shipping_amount' => 5.00,
            'amount' => 114.98,
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'discount_description' => '10% off discount',
            'note' => 'Please deliver to the back door',
            'is_confirmed' => true,
            'cancellation_reason' => 'Customer changed their mind',
        ];
    }

    protected function getPaymentStatusUpdatedSampleData(): array
    {
        return [
            'id' => 12345,
            'status' => [
                'value' => 'processing',
                'text' => 'Processing',
            ],
            'shipping_status' => [
                'value' => 'arranging_shipment',
                'text' => 'Arranging Shipment',
            ],
            'payment_method' => [
                'value' => 'stripe',
                'text' => 'Stripe',
            ],
            'payment_status' => [
                'value' => 'paid',
                'text' => 'Paid',
            ],
            'customer' => [
                'id' => 1,
                'name' => 'John Doe',
            ],
            'sub_total' => 99.99,
            'tax_amount' => 9.99,
            'shipping_method' => 'standard',
            'shipping_option' => 'Standard Shipping (5-7 days)',
            'shipping_amount' => 5.00,
            'amount' => 114.98,
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'discount_description' => '10% off discount',
            'note' => 'Please deliver to the back door',
            'is_confirmed' => true,
        ];
    }

    protected function getAbandonedCartSampleData(): array
    {
        return [
            'id' => 123,
            'customer' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
            ],
            'cart_items' => [
                [
                    'product_id' => 10,
                    'product_name' => 'T-Shirt',
                    'product_sku' => 'TS-001',
                    'quantity' => 2,
                    'price' => 25.00,
                    'total' => 50.00,
                ],
                [
                    'product_id' => 15,
                    'product_name' => 'Jeans',
                    'product_sku' => 'JN-002',
                    'quantity' => 1,
                    'price' => 75.00,
                    'total' => 75.00,
                ],
            ],
            'total_amount' => 125.00,
            'items_count' => 3,
            'abandoned_at' => '2025-01-15T10:30:00Z',
            'hours_since_abandoned' => 2,
            'reminders_sent' => 0,
            'recovery_url' => 'https://example.com/cart/recover?token=abc123',
        ];
    }

    protected function getWebhookTestScript(): string
    {
        $pleaseEnterUrlText = trans('plugins/ecommerce::setting.webhook.please_enter_url');
        $testingText = trans('plugins/ecommerce::setting.webhook.testing');
        $testFailedText = trans('plugins/ecommerce::setting.webhook.test_failed_title');
        $testSuccessText = trans('plugins/ecommerce::setting.webhook.test_success_title');
        $statusCodeText = trans('plugins/ecommerce::setting.webhook.status_code');
        $errorOccurredText = trans('plugins/ecommerce::setting.webhook.error_occurred');

        $loaderIcon = '<span style="display: inline-block; animation: spin 1s linear infinite;">' . BaseHelper::renderIcon('ti ti-loader-2', attributes: ['class' => 'm-0']) . '</span>';
        $loaderIcon = json_encode($loaderIcon);

        return <<<HTML
            <style>
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
            </style>
            <script>
                $(document).ready(function() {
                    const loaderIcon = {$loaderIcon};

                    $('.test-webhook-btn').on('click', function() {
                        const button = $(this);
                        const webhookType = button.data('webhook-type');
                        const urlFieldId = button.data('url-field');
                        const testUrl = button.data('test-url');
                        const webhookUrl = $('#' + urlFieldId).val();
                        const resultDiv = $('#test-result-' + webhookType);
                        const originalButtonHtml = button.html();

                        if (!webhookUrl) {
                            Botble.showError('{$pleaseEnterUrlText}');
                            return;
                        }

                        button.prop('disabled', true);
                        button.html(loaderIcon + ' {$testingText}');
                        resultDiv.hide();

                        $.ajax({
                            url: testUrl,
                            type: 'POST',
                            data: {
                                webhook_url: webhookUrl,
                                webhook_type: webhookType,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.error) {
                                    resultDiv.html('<div class="alert alert-danger">' +
                                        '<p><strong>{$testFailedText}</strong> <br>' + response.message +
                                        (response.data ? '<br>{$statusCodeText}: ' + response.data.status_code : '') +
                                        '</p> </div>');
                                } else {
                                    resultDiv.html('<div class="alert alert-success">' +
                                        '<strong>{$testSuccessText}</strong> ' + response.message +
                                        '<br>{$statusCodeText}: ' + response.data.status_code +
                                        '</div>');
                                }
                                resultDiv.slideDown();
                            },
                            error: function(xhr) {
                                let errorMessage = '{$errorOccurredText}';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                resultDiv.html('<div class="alert alert-danger">' +
                                    '<strong>{$testFailedText}</strong> ' + errorMessage +
                                    '</div>');
                                resultDiv.slideDown();
                            },
                            complete: function() {
                                button.prop('disabled', false);
                                button.html(originalButtonHtml);
                            }
                        });
                    });
                });
            </script>
        HTML;
    }
}
