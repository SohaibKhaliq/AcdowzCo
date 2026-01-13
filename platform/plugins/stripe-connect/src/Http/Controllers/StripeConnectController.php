<?php

namespace Botble\StripeConnect\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Customer;
use Botble\StripeConnect\StripeConnect;
use Exception;
use Stripe\Exception\ApiErrorException;

class StripeConnectController extends BaseController
{
    protected StripeConnect $stripeConnect;

    public function initialize(): void
    {
        try {
            $this->stripeConnect = new StripeConnect();
        } catch (Exception $e) {
            BaseHelper::logError($e);
            abort(404);
        }
    }

    public function connect(): BaseHttpResponse
    {
        $this->initialize();

        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        if (! $customer->stripe_account_id) {
            $customer->stripe_account_id = $this->stripeConnect->createAccount()->id;
            $customer->save();
        }

        abort_if($customer->stripe_account_active, 404);

        try {
            $url = $this->stripeConnect->createAccountLink($customer->stripe_account_id)->url;

            return $this
                ->httpResponse()
                ->setNextUrl($url);
        } catch (ApiErrorException $e) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function dashboard(): BaseHttpResponse
    {
        $this->initialize();

        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        abort_unless($customer->stripe_account_active, 404);

        $url = $this->stripeConnect->createLoginLink($customer->stripe_account_id)->url;

        return $this
            ->httpResponse()
            ->setNextUrl($url);
    }

    public function refresh(): BaseHttpResponse
    {
        $this->initialize();

        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        abort_unless($customer->stripe_account_active, 404);

        $url = $this->stripeConnect->createAccountLink($customer->stripe_account_id)->url;

        return $this
            ->httpResponse()
            ->setNextUrl($url);
    }

    public function return(): BaseHttpResponse
    {
        $this->initialize();

        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        abort_if($customer->stripe_account_active, 404);

        $account = $this->stripeConnect->retrieveAccount($customer->stripe_account_id);

        if ($account->details_submitted) {
            $customer->stripe_account_active = true;
            $customer->save();

            $customer->vendorInfo->payout_payment_method = 'stripe';
            $customer->vendorInfo->bank_info = ['stripe_account_id' => $customer->stripe_account_id];
            $customer->vendorInfo->save();
        }

        return $this
            ->httpResponse()
            ->setNextRoute('marketplace.vendor.dashboard')
            ->setMessage(trans('plugins/stripe-connect::stripe-connect.notifications.now_active'));
    }

    public function disconnect(): BaseHttpResponse
    {
        $this->initialize();

        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        abort_unless($customer->stripe_account_active, 404);

        $this->stripeConnect->deleteAccount($customer->stripe_account_id);

        $customer->stripe_account_id = null;
        $customer->stripe_account_active = false;
        $customer->vendorInfo->bank_info = null;
        $customer->save();

        return $this
            ->httpResponse()
            ->setNextRoute('marketplace.vendor.dashboard')
            ->setMessage(trans('plugins/stripe-connect::stripe-connect.notifications.disconnected'));
    }
}
