<?php

namespace Botble\Mollie\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Payment\Supports\PaymentHelper;
use Exception;
use Illuminate\Http\Request;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Types\PaymentStatus;
use Mollie\Laravel\Facades\Mollie;

class MollieController extends BaseController
{
    protected function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = ['card', 'iban', 'bic', 'consumerAccount', 'cardNumber', 'cardHolder', 'cvv'];

        array_walk_recursive($data, function (&$value, $key) use ($sensitiveKeys) {
            if (in_array($key, $sensitiveKeys) && is_string($value)) {
                $value = substr($value, 0, 4) . str_repeat('*', max(0, strlen($value) - 4));
            }
        });

        return $data;
    }

    protected function logRequest(array $data, ?string $token): void
    {
        $request = request();

        $data['payment_id'] = $request->input('id');
        $data['post_params'] = $request->all();
        $data['raw_content'] = $request->getContent();
        $data['token'] = $token;

        PaymentHelper::log(
            MOLLIE_PAYMENT_METHOD_NAME,
            [],
            $this->sanitizeLogData($data)
        );
    }

    public function webhook(string $token, Request $request)
    {
        try {
            $paymentId = $request->input('id');

            $webhookSecret = get_payment_setting('webhook_secret', MOLLIE_PAYMENT_METHOD_NAME);
            if ($webhookSecret) {
                $signature = $request->header('X-Mollie-Signature');

                if (! $signature) {
                    $this->logRequest([
                        'webhook_error' => 'Missing signature header',
                        'headers' => $request->headers->all(),
                    ], $token);

                    return response('Missing signature', 400);
                }

                $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);

                if (! hash_equals($expectedSignature, $signature)) {
                    $this->logRequest([
                        'webhook_error' => 'Invalid signature',
                        'received_signature' => $signature,
                        'expected_signature' => $expectedSignature,
                    ], $token);

                    return response('Invalid signature', 403);
                }
            }

            if (! $paymentId) {
                $this->logRequest([
                    'webhook_error' => 'Missing payment ID',
                ], $token);

                return response('Missing payment ID', 400);
            }

            $this->logRequest([
                'webhook_processing' => 'Starting payment processing',
            ], $token);

            $payment = Payment::query()->where('charge_id', $paymentId)->first();

            if ($payment && in_array($payment->status, [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::REFUNDED])) {
                $api = Mollie::api();

                do_action('payment_before_making_api_request', MOLLIE_PAYMENT_METHOD_NAME, ['payment_id' => $paymentId]);

                $result = $api->payments->get($paymentId);

                do_action(
                    'payment_after_api_response',
                    MOLLIE_PAYMENT_METHOD_NAME,
                    ['payment_id' => $paymentId],
                    (array) $result
                );

                if (isset($result->amountRefunded) && (float) $result->amountRefunded->value > 0) {
                    $isFullyRefunded = (float) $result->amountRefunded->value >= (float) $result->amount->value;

                    if ($payment->status != PaymentStatusEnum::REFUNDED || ! $isFullyRefunded) {
                        $newStatus = $isFullyRefunded ? PaymentStatusEnum::REFUNDED : PaymentStatusEnum::REFUNDING;

                        if ($payment->status != $newStatus) {
                            $this->logRequest([
                                'webhook_refund_detected' => true,
                                'amount_refunded' => $result->amountRefunded->value,
                                'total_amount' => $result->amount->value,
                                'is_fully_refunded' => $isFullyRefunded,
                                'new_status' => $newStatus,
                            ], $token);

                            do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                                'amount' => $result->amount->value,
                                'currency' => $result->amount->currency,
                                'charge_id' => $result->id,
                                'payment_channel' => MOLLIE_PAYMENT_METHOD_NAME,
                                'status' => $newStatus,
                                'customer_id' => $result->metadata->customer_id ?? null,
                                'customer_type' => $result->metadata->customer_type ?? null,
                                'payment_type' => 'direct',
                                'order_id' => isset($result->metadata->order_id) ? (array) $result->metadata->order_id : [],
                                'refund_amount' => $result->amountRefunded->value ?? 0,
                                'is_refund_update' => true,
                            ]);
                        }
                    }

                    return response('OK', 200);
                }

                if ($payment->status == PaymentStatusEnum::COMPLETED) {
                    $this->logRequest([
                        'webhook_info' => 'Payment already completed in database and no refunds detected, skipping webhook processing',
                        'local_status' => $payment->status->getValue(),
                    ], $token);

                    return response('OK', 200);
                }
            }

            $api = Mollie::api();

            do_action('payment_before_making_api_request', MOLLIE_PAYMENT_METHOD_NAME, ['payment_id' => $paymentId]);

            $result = $api->payments->get($paymentId);

            do_action(
                'payment_after_api_response',
                MOLLIE_PAYMENT_METHOD_NAME,
                ['payment_id' => $paymentId],
                (array) $result
            );

            $this->logRequest([
                'webhook_payment_status' => $result->status,
                'mollie_response' => (array) $result,
            ], $token);

            if (in_array($result->status, [
                PaymentStatus::STATUS_CANCELED,
                PaymentStatus::STATUS_EXPIRED,
                PaymentStatus::STATUS_FAILED,
            ])) {
                $this->logRequest([
                    'webhook_payment_failed' => $result->status,
                    'mollie_details' => (array) $result,
                ], $token);

                if ($payment) {
                    do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                        'amount' => $result->amount->value,
                        'currency' => $result->amount->currency,
                        'charge_id' => $result->id,
                        'payment_channel' => MOLLIE_PAYMENT_METHOD_NAME,
                        'status' => PaymentStatusEnum::FAILED,
                        'customer_id' => $result->metadata->customer_id ?? null,
                        'customer_type' => $result->metadata->customer_type ?? null,
                        'payment_type' => 'direct',
                        'order_id' => isset($result->metadata->order_id) ? (array) $result->metadata->order_id : [],
                    ]);
                }

                return response('OK', 200);
            }

            if ($result->isPaid() || $result->isAuthorized()) {
                $status = PaymentStatusEnum::COMPLETED;

                if (isset($result->amountRefunded) && (float) $result->amountRefunded->value > 0) {
                    $isFullyRefunded = (float) $result->amountRefunded->value >= (float) $result->amount->value;
                    $status = $isFullyRefunded ? PaymentStatusEnum::REFUNDED : PaymentStatusEnum::REFUNDING;

                    $this->logRequest([
                        'webhook_refund_status' => true,
                        'amount_refunded' => $result->amountRefunded->value,
                        'total_amount' => $result->amount->value,
                        'is_fully_refunded' => $isFullyRefunded,
                        'status' => $status,
                    ], $token);
                } elseif (in_array($result->status, [PaymentStatus::STATUS_OPEN, PaymentStatus::STATUS_AUTHORIZED])) {
                    $status = PaymentStatusEnum::PENDING;
                }

                $orderIds = isset($result->metadata->order_id) ? (array) $result->metadata->order_id : [];

                do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                    'amount' => $result->amount->value,
                    'currency' => $result->amount->currency,
                    'charge_id' => $result->id,
                    'payment_channel' => MOLLIE_PAYMENT_METHOD_NAME,
                    'status' => $status,
                    'customer_id' => $result->metadata->customer_id ?? null,
                    'customer_type' => $result->metadata->customer_type ?? null,
                    'payment_type' => 'direct',
                    'order_id' => $orderIds,
                    'refund_amount' => $result->amountRefunded->value ?? 0,
                    'is_refund_update' => isset($result->amountRefunded) && (float) $result->amountRefunded->value > 0,
                ]);

                $this->logRequest([
                    'webhook_payment_processed' => $status,
                    'order_ids' => $orderIds,
                    'mollie_details' => (array) $result,
                ], $token);
            }

            return response('OK', 200);
        } catch (ApiException $exception) {
            BaseHelper::logError($exception);

            $this->logRequest([
                'webhook_exception' => $exception->getMessage(),
                'exception_trace' => $exception->getTraceAsString(),
                'headers' => $request->headers->all(),
            ], $token);

            if (str_contains($exception->getMessage(), 'not found')) {
                return response('OK', 200);
            }

            return response('Internal server error', 500);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);

            $this->logRequest([
                'webhook_unexpected_error' => $exception->getMessage(),
                'exception_trace' => $exception->getTraceAsString(),
                'headers' => $request->headers->all(),
            ], $token);

            return response('Internal server error', 500);
        }
    }
}
