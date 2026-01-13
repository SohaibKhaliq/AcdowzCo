<?php

namespace Botble\PayPalPayout\Listeners;

use Botble\AffiliatePro\Enums\PayoutPaymentMethodsEnum;
use Botble\AffiliatePro\Enums\WithdrawalStatusEnum;
use Botble\AffiliatePro\Events\WithdrawalApprovedEvent;
use Botble\AffiliatePro\Events\WithdrawalRequestedEvent;
use Botble\Base\Facades\BaseHelper;
use Botble\PayPal\Services\Gateways\PayPalPaymentService;
use Botble\PayPalPayout\PayPalPayoutsSDK\Payouts\PayoutsPostRequest;
use Exception;

class AffiliatePayPalPayoutListener
{
    public function handle(WithdrawalRequestedEvent $event): void
    {
        $customer = $event->customer;
        $withdrawal = $event->withdrawal;

        if (
            ! is_plugin_active('affiliate-pro') ||
            $withdrawal->payment_channel !== PayoutPaymentMethodsEnum::PAYPAL ||
            ! $withdrawal->bank_info ||
            ! isset($withdrawal->bank_info['paypal_id'])
        ) {
            return;
        }

        try {
            $payPalPaymentService = app(PayPalPaymentService::class);
            $client = $payPalPaymentService->getClient();

            $request = new PayoutsPostRequest();
            $request->body = json_decode(
                '{
                "sender_batch_header":
                {
                  "email_subject": "' . __('You have money!') . '",
                  "email_message": "' . __('You received a payment. Thanks for being an affiliate on our site!') . '"
                },
                "items": [
                {
                      "recipient_type": "EMAIL",
                      "amount": {
                        "value": "' . ((string) $withdrawal->amount) . '",
                        "currency": "' . $withdrawal->currency . '"
                      },
                      "note": "Thanks for being an affiliate on our site!",
                      "sender_item_id": "' . $withdrawal->id . '",
                      "receiver": "' . $withdrawal->bank_info['paypal_id'] . '"
                  }
                ]
              }',
                true
            );

            do_action('payment_before_making_api_request', PAYPAL_PAYMENT_METHOD_NAME, $request);

            $result = $client->execute($request);

            do_action('payment_after_api_response', PAYPAL_PAYMENT_METHOD_NAME, (array) $request, (array) $result);

            $withdrawal->status = WithdrawalStatusEnum::APPROVED;
            $withdrawal->transaction_id = $result->result->batch_header->payout_batch_id; // @phpstan-ignore-line
            $withdrawal->save();

            // Update affiliate total_withdrawn (balance was already deducted when withdrawal was created)
            $affiliate = $withdrawal->affiliate;
            if ($affiliate) {
                $affiliate->total_withdrawn += $withdrawal->amount;
                $affiliate->save();

                // Create transaction record
                $affiliate->transactions()->create([
                    'amount' => -$withdrawal->amount,
                    'description' => 'Withdrawal approved via PayPal: ' . $withdrawal->transaction_id,
                    'type' => 'withdrawal',
                    'reference_id' => $withdrawal->id,
                    'reference_type' => get_class($withdrawal),
                ]);
            }

            // Fire withdrawal approved event
            event(new WithdrawalApprovedEvent($withdrawal));
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
