<?php

namespace Botble\Razorpay\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Botble\Payment\Supports\PaymentHelper;
use Exception;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends BaseController
{
    /**
     * Link orphaned payment with order
     */
    protected function linkPaymentWithOrder($chargeId, $orderId)
    {
        if (! $chargeId || ! $orderId) {
            return;
        }

        $payment = Payment::query()
            ->where('charge_id', $chargeId)
            ->whereNull('order_id')
            ->first();

        if ($payment) {
            $payment->order_id = is_array($orderId) ? ($orderId[0] ?? null) : $orderId;
            $payment->save();

            PaymentHelper::log(
                RAZORPAY_PAYMENT_METHOD_NAME,
                ['info' => 'Linked orphaned payment with order'],
                ['charge_id' => $chargeId, 'order_id' => $orderId]
            );
        }
    }
    public function callback(
        string $token,
        Request $request,
        BaseHttpResponse $response
    ): BaseHttpResponse {
        // Log the callback request for debugging
        PaymentHelper::log(
            RAZORPAY_PAYMENT_METHOD_NAME,
            ['callback_request' => $request->all()],
            ['token' => $token, 'headers' => $request->headers->all()]
        );

        if ($request->input('error.description')) {
            $message = $request->input('error.code') . ': ' . $request->input('error.description');

            // Log payment error
            PaymentHelper::log(
                RAZORPAY_PAYMENT_METHOD_NAME,
                ['error' => $request->input('error')],
                ['token' => $token, 'message' => $message]
            );

            return $this
                ->httpResponse()
                ->setNextUrl(PaymentHelper::getCancelURL($token) . '&error_message=' . $message)
                ->withInput()
                ->setMessage($message);
        }

        $chargeId = $request->input('razorpay_payment_id');

        if (! $chargeId) {
            // Log missing payment ID
            PaymentHelper::log(
                RAZORPAY_PAYMENT_METHOD_NAME,
                ['error' => 'Missing payment ID'],
                ['token' => $token]
            );

            return $response
                ->setNextUrl(PaymentHelper::getCancelURL($token))
                ->withInput()
                ->setMessage(__('Payment failed!'));
        }

        $orderId = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');

        try {
            if ($orderId && $signature) {
                // Log signature verification attempt
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    [
                        'verification_attempt' => [
                            'payment_id' => $chargeId,
                            'order_id' => $orderId,
                        ],
                    ],
                    ['token' => $token]
                );

                $status = PaymentStatusEnum::PENDING;

                $apiKey = get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME);
                $apiSecret = get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME);

                $api = new Api($apiKey, $apiSecret);

                // @phpstan-ignore-next-line
                $api->utility->verifyPaymentSignature([
                    'razorpay_signature' => $signature,
                    'razorpay_payment_id' => $chargeId,
                    'razorpay_order_id' => $orderId,
                ]);

                // Log successful signature verification
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    ['signature_verification' => 'success'],
                    ['payment_id' => $chargeId, 'order_id' => $orderId]
                );

                do_action('payment_before_making_api_request', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId]);

                // @phpstan-ignore-next-line
                $order = $api->order->fetch($orderId);

                $order = $order->toArray();

                do_action('payment_after_api_response', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId], $order);

                // Log order details
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    ['order_details' => ['order_id' => $orderId]],
                    ['order_data' => $order]
                );

                $amount = $order['amount_paid'] / 100;

                $status = $order['status'] === 'paid' ? PaymentStatusEnum::COMPLETED : $status;

                $orderId = $request->input('order_id');

                if (! $orderId && class_exists(Order::class)) {
                    // First try to find by receipt (which should be the checkout token)
                    $orderId = Order::query()->where('token', $order['receipt'])->pluck('id')->all();

                    // Log order lookup
                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['order_lookup' => ['receipt' => $order['receipt']]],
                        ['found_order_ids' => $orderId]
                    );
                }

                // If still no order found, try from the token in the URL
                if (empty($orderId) && class_exists(Order::class)) {
                    $orderId = Order::query()->where('token', $token)->pluck('id')->all();

                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['order_lookup_by_token' => ['token' => $token]],
                        ['found_order_ids' => $orderId]
                    );
                }

                // Log payment processing
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    [
                        'processing_payment' => [
                            'amount' => $amount,
                            'currency' => $order['currency'],
                            'status' => $status,
                        ],
                    ],
                    [
                        'charge_id' => $chargeId,
                        'order_id' => $orderId,
                    ]
                );

                // Always try to process payment even if order not found
                if (empty($orderId)) {
                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['warning' => 'No order found for payment, but processing anyway'],
                        [
                            'payment_id' => $chargeId,
                            'receipt' => $order['receipt'],
                            'token' => $token,
                        ]
                    );

                    // Try to create order from the session if it exists
                    if (class_exists(Order::class)) {
                        $sessionOrder = Order::query()
                            ->where('token', $token)
                            ->where('is_finished', false)
                            ->first();

                        if ($sessionOrder) {
                            $orderId = [$sessionOrder->id];
                            PaymentHelper::log(
                                RAZORPAY_PAYMENT_METHOD_NAME,
                                ['info' => 'Found incomplete order from session'],
                                ['order_id' => $orderId]
                            );

                            // Link any orphaned payment with this order
                            $this->linkPaymentWithOrder($chargeId, $orderId);
                        }
                    }
                }

                // Note: Not calling PAYMENT_ACTION_PAYMENT_PROCESSED to avoid duplicate payment creation
                // We handle payment storage manually in this controller
            } else {
                // Log missing order ID or signature
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    ['warning' => 'Missing order ID or signature, attempting direct payment fetch'],
                    [
                        'token' => $token,
                        'has_order_id' => (bool) $orderId,
                        'has_signature' => (bool) $signature,
                        'payment_id' => $chargeId,
                    ]
                );

                // Try to fetch payment details directly without signature verification
                try {
                    $apiKey = get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME);
                    $apiSecret = get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME);
                    $api = new Api($apiKey, $apiSecret);

                    $payment = $api->payment->fetch($chargeId);

                    if ($payment && ($payment->status === 'captured' || $payment->status === 'authorized')) {
                        $amount = $payment->amount / 100;
                        $status = PaymentStatusEnum::COMPLETED;

                        $orderId = $request->input('order_id');

                        // Try to get order_id from payment notes if not in request
                        if (! $orderId && $payment->notes) {
                            if (isset($payment->notes->order_id)) {
                                $orderId = $payment->notes->order_id;
                                // Handle comma-separated order IDs
                                if (is_string($orderId) && str_contains($orderId, ',')) {
                                    $orderId = explode(',', $orderId);
                                }
                            } elseif (isset($payment->notes->order_token) && class_exists(Order::class)) {
                                // Try to find order by token
                                $orderIds = Order::query()->where('token', $payment->notes->order_token)->pluck('id')->all();
                                if (! empty($orderIds)) {
                                    $orderId = $orderIds;
                                }
                            }
                        }

                        // Last resort: try to find order by token from URL
                        if (! $orderId && class_exists(Order::class)) {
                            $orderIds = Order::query()->where('token', $token)->pluck('id')->all();
                            if (! empty($orderIds)) {
                                $orderId = $orderIds;
                            }
                        }

                        // Note: Not calling PAYMENT_ACTION_PAYMENT_PROCESSED to avoid duplicate payment creation
                        // We handle payment storage manually in this controller

                        PaymentHelper::log(
                            RAZORPAY_PAYMENT_METHOD_NAME,
                            ['payment_processed_without_signature' => true],
                            [
                                'charge_id' => $chargeId,
                                'status' => $payment->status,
                            ]
                        );
                    }
                } catch (Exception $e) {
                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['error' => 'Failed to fetch payment details'],
                        [
                            'exception' => $e->getMessage(),
                            'payment_id' => $chargeId,
                        ]
                    );
                    BaseHelper::logError($e);
                }
            }
        } catch (SignatureVerificationError $exception) {
            // Log signature verification error
            PaymentHelper::log(
                RAZORPAY_PAYMENT_METHOD_NAME,
                ['error' => 'Signature verification failed'],
                [
                    'exception' => $exception->getMessage(),
                    'payment_id' => $chargeId,
                    'order_id' => $orderId,
                ]
            );

            BaseHelper::logError($exception);

            return $response
                ->setNextUrl(PaymentHelper::getCancelURL($token) . '&error_message=' . $exception->getMessage())
                ->withInput()
                ->setMessage($exception->getMessage());
        } catch (Exception $exception) {
            // Log any other exceptions
            PaymentHelper::log(
                RAZORPAY_PAYMENT_METHOD_NAME,
                ['error' => 'Payment processing failed'],
                [
                    'exception' => $exception->getMessage(),
                    'payment_id' => $chargeId,
                    'token' => $token,
                ]
            );

            BaseHelper::logError($exception);

            // Still try to process the payment if we have a charge ID
            if ($chargeId) {
                try {
                    $apiKey = get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME);
                    $apiSecret = get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME);
                    $api = new Api($apiKey, $apiSecret);

                    // Fetch payment details directly
                    $payment = $api->payment->fetch($chargeId);

                    if ($payment && $payment->status === 'captured') {
                        $amount = $payment->amount / 100;
                        $status = PaymentStatusEnum::COMPLETED;

                        $orderId = $request->input('order_id');

                        // Try to get order_id from payment notes if not in request
                        if (! $orderId && $payment->notes) {
                            if (isset($payment->notes->order_id)) {
                                $orderId = $payment->notes->order_id;
                                // Handle comma-separated order IDs
                                if (is_string($orderId) && str_contains($orderId, ',')) {
                                    $orderId = explode(',', $orderId);
                                }
                            } elseif (isset($payment->notes->order_token) && class_exists(Order::class)) {
                                // Try to find order by token
                                $orderIds = Order::query()->where('token', $payment->notes->order_token)->pluck('id')->all();
                                if (! empty($orderIds)) {
                                    $orderId = $orderIds;
                                }
                            }
                        }

                        // Last resort: try to find order by token from URL
                        if (! $orderId && class_exists(Order::class)) {
                            $orderIds = Order::query()->where('token', $token)->pluck('id')->all();
                            if (! empty($orderIds)) {
                                $orderId = $orderIds;
                            }
                        }

                        // Note: Not calling PAYMENT_ACTION_PAYMENT_PROCESSED to avoid duplicate payment creation
                        // We handle payment storage manually in this controller

                        return $response
                            ->setNextUrl(PaymentHelper::getRedirectURL($token) . '?charge_id=' . $chargeId)
                            ->setMessage(__('Checkout successfully!'));
                    }
                } catch (Exception $e) {
                    BaseHelper::logError($e);
                }
            }

            return $response
                ->setNextUrl(PaymentHelper::getCancelURL($token) . '&error_message=' . $exception->getMessage())
                ->withInput()
                ->setMessage(__('Payment failed!'));
        }

        // Log successful checkout
        PaymentHelper::log(
            RAZORPAY_PAYMENT_METHOD_NAME,
            ['success' => 'Payment processed successfully'],
            [
                'charge_id' => $chargeId,
                'token' => $token,
            ]
        );

        // Ensure payment is stored in database
        if ($chargeId && isset($status)) {
            $paymentData = [
                'amount' => $amount ?? 0,
                'currency' => isset($order) ? $order['currency'] : 'USD',
                'charge_id' => $chargeId,
                'payment_channel' => RAZORPAY_PAYMENT_METHOD_NAME,
                'status' => $status,
                'order_id' => is_array($orderId) ? ($orderId[0] ?? null) : $orderId,
                'customer_id' => $request->input('customer_id'),
                'customer_type' => $request->input('customer_type'),
            ];

            // Check if payment already exists by charge_id only
            $existingPayment = Payment::query()
                ->where('charge_id', $chargeId)
                ->first();

            if (! $existingPayment) {
                // Create new payment record
                $payment = new Payment();
                $payment->fill($paymentData);
                $payment->save();

                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    ['info' => 'Payment record created in callback'],
                    ['charge_id' => $chargeId, 'order_id' => $orderId]
                );
            } else {
                // Update existing payment with complete information
                $updated = false;

                // Update amount if it was preliminary (0)
                if ($existingPayment->amount == 0 && $paymentData['amount'] > 0) {
                    $existingPayment->amount = $paymentData['amount'];
                    $updated = true;
                }

                // Update currency if it wasn't set properly
                if ($existingPayment->currency !== $paymentData['currency']) {
                    $existingPayment->currency = $paymentData['currency'];
                    $updated = true;
                }

                // Update status if it changed
                if ($existingPayment->status !== $status) {
                    $existingPayment->status = $status;
                    $updated = true;
                }

                // Update order_id if it was missing and we now have one
                if (! $existingPayment->order_id && $paymentData['order_id']) {
                    $existingPayment->order_id = $paymentData['order_id'];
                    $updated = true;
                }

                // Update customer info if missing
                if (! $existingPayment->customer_id && $paymentData['customer_id']) {
                    $existingPayment->customer_id = $paymentData['customer_id'];
                    $existingPayment->customer_type = $paymentData['customer_type'];
                    $updated = true;
                }

                if ($updated) {
                    $existingPayment->save();

                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['info' => 'Payment record updated in callback'],
                        ['charge_id' => $chargeId, 'order_id' => $orderId, 'status' => $status, 'amount' => $paymentData['amount']]
                    );
                }
            }
        }

        // Ensure order is marked as finished if payment was successful
        if ($status === PaymentStatusEnum::COMPLETED && ! empty($orderId) && class_exists(Order::class)) {
            $orders = Order::query()->whereIn('id', (array) $orderId)->where('is_finished', false)->get();
            if ($orders->isNotEmpty()) {
                foreach ($orders as $order) {
                    $order->is_finished = true;
                    $order->save();

                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['info' => 'Marked order as finished in callback'],
                        ['order_id' => $order->id]
                    );
                }

                // Process orders manually since we're not using PAYMENT_ACTION_PAYMENT_PROCESSED
                if (class_exists(OrderHelper::class)) {
                    OrderHelper::processOrder($orders->pluck('id')->all(), $chargeId);
                }
            }
        }

        // Final safety check: ensure any orphaned payments are linked
        if ($chargeId) {
            $this->linkPaymentWithOrder($chargeId, $orderId);
        }

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL($token) . '?charge_id=' . $chargeId)
            ->setMessage(__('Checkout successfully!'));
    }

    public function webhook(Request $request)
    {
        $webhookSecret = get_payment_setting('webhook_secret', RAZORPAY_PAYMENT_METHOD_NAME);
        $signature = $request->header('X-Razorpay-Signature');
        $content = $request->getContent();

        // Log the webhook request for debugging
        PaymentHelper::log(
            RAZORPAY_PAYMENT_METHOD_NAME,
            ['webhook_request' => $request->all()],
            ['headers' => $request->headers->all()]
        );

        // Signature verification (if webhook secret is configured)
        if ($webhookSecret && $signature && $content) {
            try {
                // Verify webhook signature
                $expectedSignature = hash_hmac('sha256', $content, $webhookSecret);
                if (hash_equals($expectedSignature, $signature)) {
                    // Log successful signature verification
                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['webhook_signature_verification' => 'success'],
                        ['signature' => substr($signature, 0, 10) . '...'] // Only log part of the signature for security
                    );
                } else {
                    BaseHelper::logError(new Exception('Invalid webhook signature'));

                    return response('Invalid signature', 400);
                }
            } catch (Exception $exception) {
                BaseHelper::logError($exception);

                return response('Error verifying webhook signature: ' . $exception->getMessage(), 400);
            }
        } else {
            // For backward compatibility, proceed without signature verification
            // but log a warning about missing webhook secret
            if (! $webhookSecret) {
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    ['webhook_warning' => 'No webhook secret configured'],
                    ['recommendation' => 'Configure webhook secret for secure webhook verification']
                );
            }

            // If we have a signature but no secret, log this unusual situation
            if ($signature && ! $webhookSecret) {
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    ['webhook_warning' => 'Signature provided but no webhook secret configured'],
                    ['signature_prefix' => substr($signature, 0, 10) . '...']
                );
            }
        }

        // Process the webhook event
        try {
            $event = $request->input('event');
            $eventId = $request->header('X-Razorpay-Event-Id');

            // Check for duplicate webhook events (idempotency)
            if ($eventId) {
                // Log the event ID for tracking
                PaymentHelper::log(
                    RAZORPAY_PAYMENT_METHOD_NAME,
                    ['webhook_event_id' => $eventId],
                    ['event' => $event]
                );

                // Here you could check if this event ID has been processed before
                // For example, by checking a database table of processed webhook events
                // If implemented, this would prevent duplicate processing
            }

            $api = new Api(
                get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME),
                get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME)
            );

            // Handle different event types
            switch ($event) {
                case 'payment.authorized':
                case 'payment.captured':
                case 'payment.failed':
                case 'payment.pending':
                case 'order.paid':
                    try {
                        // Log the specific event type
                        PaymentHelper::log(
                            RAZORPAY_PAYMENT_METHOD_NAME,
                            ['event_type' => $event],
                            ['payload' => $request->input('payload')]
                        );

                        $paymentEntity = $request->input('payload.payment.entity');
                        if (! $paymentEntity) {
                            return response('No payment entity found', 400);
                        }

                        $chargeId = $paymentEntity['id'];
                        $orderId = $paymentEntity['order_id'];

                        if (! $orderId) {
                            return response('No order ID found', 400);
                        }

                        do_action('payment_before_making_api_request', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId]);

                        // @phpstan-ignore-next-line
                        $order = $api->order->fetch($orderId);
                        $orderData = $order->toArray();

                        do_action('payment_after_api_response', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId], $orderData);

                        $status = PaymentStatusEnum::PENDING;

                        // Log payment status from Razorpay
                        PaymentHelper::log(
                            RAZORPAY_PAYMENT_METHOD_NAME,
                            ['payment_status_check' => [
                                'payment_status' => $paymentEntity['status'] ?? 'unknown',
                                'order_status' => $orderData['status'] ?? 'unknown',
                            ]],
                            ['charge_id' => $chargeId]
                        );

                        // Check payment status with more detailed conditions
                        if ($paymentEntity['status'] === 'captured' || $paymentEntity['status'] === 'authorized' || $orderData['status'] === 'paid') {
                            $status = PaymentStatusEnum::COMPLETED;

                            // If payment is authorized but not captured, try to capture it
                            if ($paymentEntity['status'] === 'authorized' && ! $paymentEntity['captured']) {
                                try {
                                    $api->payment->capture($chargeId, ['amount' => $paymentEntity['amount']]);
                                    PaymentHelper::log(
                                        RAZORPAY_PAYMENT_METHOD_NAME,
                                        ['payment_captured' => true],
                                        ['charge_id' => $chargeId]
                                    );
                                } catch (Exception $e) {
                                    PaymentHelper::log(
                                        RAZORPAY_PAYMENT_METHOD_NAME,
                                        ['capture_error' => $e->getMessage()],
                                        ['charge_id' => $chargeId]
                                    );
                                }
                            }
                        } elseif ($paymentEntity['status'] === 'failed') {
                            $status = PaymentStatusEnum::FAILED;
                        } elseif ($paymentEntity['status'] === 'refunded') {
                            $status = PaymentStatusEnum::REFUNDED;
                        }

                        $payment = Payment::query()
                            ->where('charge_id', $chargeId)
                            ->first();

                        if ($payment) {
                            $payment->status = $status;
                            $payment->save();

                            $orderId = $payment->order_id;
                        } elseif (class_exists(Order::class)) {
                            // First try to find by receipt (order token)
                            $orderId = Order::query()->where('token', $orderData['receipt'])->pluck('id')->all();

                            PaymentHelper::log(
                                RAZORPAY_PAYMENT_METHOD_NAME,
                                ['order_lookup_by_receipt' => [
                                    'receipt' => $orderData['receipt'],
                                    'found_order_ids' => $orderId,
                                ]],
                                ['method' => 'webhook']
                            );

                            // If not found, try to get order info from payment notes
                            if (empty($orderId) && isset($paymentEntity['notes']) && class_exists(Order::class)) {
                                if (isset($paymentEntity['notes']['order_token'])) {
                                    $orderId = Order::query()->where('token', $paymentEntity['notes']['order_token'])->pluck('id')->all();
                                } elseif (isset($paymentEntity['notes']['order_id'])) {
                                    // Parse order_id from notes (could be comma-separated)
                                    $orderIdFromNotes = $paymentEntity['notes']['order_id'];
                                    if (is_string($orderIdFromNotes) && str_contains($orderIdFromNotes, ',')) {
                                        $orderId = explode(',', $orderIdFromNotes);
                                    } else {
                                        $orderId = (array) $orderIdFromNotes;
                                    }
                                }
                            }
                        }

                        // Process payment even if order not found to avoid losing payments
                        $amount = isset($orderData['amount_paid']) ? $orderData['amount_paid'] / 100 : ($paymentEntity['amount'] / 100);
                        $currency = $orderData['currency'] ?? $paymentEntity['currency'];

                        // Try to get customer info from the order
                        $customerInfo = [];
                        if ($orderId && class_exists(Order::class)) {
                            $order = Order::query()->whereIn('id', (array) $orderId)->first();
                            if ($order && $order->user_id) {
                                $customerInfo['customer_id'] = $order->user_id;
                                $customerInfo['customer_type'] = $order->user_type;
                            }
                        }

                        // Link any orphaned payment with order if we found one
                        if ($orderId) {
                            $this->linkPaymentWithOrder($chargeId, $orderId);
                        }

                        // Ensure payment is stored in database
                        $paymentData = [
                            'amount' => $amount,
                            'currency' => $currency,
                            'charge_id' => $chargeId,
                            'payment_channel' => RAZORPAY_PAYMENT_METHOD_NAME,
                            'status' => $status,
                            'order_id' => is_array($orderId) ? ($orderId[0] ?? null) : $orderId,
                            'customer_id' => $customerInfo['customer_id'] ?? null,
                            'customer_type' => $customerInfo['customer_type'] ?? null,
                        ];

                        // Check if payment already exists by charge_id only
                        $existingPayment = Payment::query()
                            ->where('charge_id', $chargeId)
                            ->first();

                        if (! $existingPayment) {
                            // Create new payment record
                            $payment = new Payment();
                            $payment->fill($paymentData);
                            $payment->save();

                            PaymentHelper::log(
                                RAZORPAY_PAYMENT_METHOD_NAME,
                                ['info' => 'Payment record created in webhook'],
                                ['charge_id' => $chargeId, 'order_id' => $orderId]
                            );
                        } else {
                            // Update existing payment with complete information
                            $updated = false;

                            // Update amount if it was preliminary (0)
                            if ($existingPayment->amount == 0 && $paymentData['amount'] > 0) {
                                $existingPayment->amount = $paymentData['amount'];
                                $updated = true;
                            }

                            // Update currency if it wasn't set properly
                            if ($existingPayment->currency !== $paymentData['currency']) {
                                $existingPayment->currency = $paymentData['currency'];
                                $updated = true;
                            }

                            // Update status if it changed
                            if ($existingPayment->status !== $status) {
                                $existingPayment->status = $status;
                                $updated = true;
                            }

                            // Update order_id if it was missing and we now have one
                            if (! $existingPayment->order_id && $paymentData['order_id']) {
                                $existingPayment->order_id = $paymentData['order_id'];
                                $updated = true;
                            }

                            // Update customer info if missing
                            if (! $existingPayment->customer_id && $paymentData['customer_id']) {
                                $existingPayment->customer_id = $paymentData['customer_id'];
                                $existingPayment->customer_type = $paymentData['customer_type'];
                                $updated = true;
                            }

                            if ($updated) {
                                $existingPayment->save();

                                PaymentHelper::log(
                                    RAZORPAY_PAYMENT_METHOD_NAME,
                                    ['info' => 'Payment record updated in webhook'],
                                    ['charge_id' => $chargeId, 'order_id' => $orderId, 'status' => $status, 'amount' => $paymentData['amount']]
                                );
                            }
                        }

                        // Note: Not calling PAYMENT_ACTION_PAYMENT_PROCESSED to avoid duplicate payment creation
                        // We handle payment storage manually in this controller

                        // Ensure order is marked as finished if payment was successful
                        if ($status === PaymentStatusEnum::COMPLETED && ! empty($orderId) && class_exists(Order::class)) {
                            $orders = Order::query()->whereIn('id', (array) $orderId)->where('is_finished', false)->get();
                            if ($orders->isNotEmpty()) {
                                foreach ($orders as $order) {
                                    $order->is_finished = true;
                                    $order->save();

                                    PaymentHelper::log(
                                        RAZORPAY_PAYMENT_METHOD_NAME,
                                        ['info' => 'Marked order as finished in webhook'],
                                        ['order_id' => $order->id]
                                    );
                                }

                                // Process orders manually since we're not using PAYMENT_ACTION_PAYMENT_PROCESSED
                                if (class_exists(OrderHelper::class)) {
                                    OrderHelper::processOrder($orders->pluck('id')->all(), $chargeId);
                                }
                            }
                        }

                        PaymentHelper::log(
                            RAZORPAY_PAYMENT_METHOD_NAME,
                            ['webhook_payment_processed' => true],
                            [
                                'charge_id' => $chargeId,
                                'order_id' => $orderId,
                                'status' => $status,
                            ]
                        );

                        return response('Webhook processed successfully');
                    } catch (BadRequestError $exception) {
                        BaseHelper::logError($exception);

                        return response('Error processing payment: ' . $exception->getMessage(), 400);
                    }
                case 'refund.created':
                    // Handle refund events if needed
                    return response('Refund event received');

                default:
                    // Log unhandled events
                    PaymentHelper::log(
                        RAZORPAY_PAYMENT_METHOD_NAME,
                        ['unhandled_event' => $event],
                        ['payload' => $request->all()]
                    );

                    return response('Event type not handled: ' . $event);
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);

            return response('Error processing webhook: ' . $exception->getMessage(), 500);
        }
    }
}
