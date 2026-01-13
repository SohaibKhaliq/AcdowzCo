<?php

namespace Botble\StripeConnect;

use Exception;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\LoginLink;
use Stripe\StripeClient;
use Stripe\Transfer;

class StripeConnect
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $secret = get_payment_setting('secret', STRIPE_PAYMENT_METHOD_NAME);

        if (empty($secret)) {
            throw new Exception('Stripe secret key is required.');
        }

        $this->stripe = new StripeClient($secret);
    }

    public function createAccount(): Account
    {
        return $this->stripe->accounts->create(['type' => 'express']);
    }

    public function createAccountLink(string $accountId): AccountLink
    {
        return $this->stripe->accountLinks->create([
            'account' => $accountId,
            'refresh_url' => route('stripe-connect.refresh'),
            'return_url' => route('stripe-connect.return'),
            'type' => 'account_onboarding',
        ]);
    }

    public function retrieveAccount(string $accountId): Account
    {
        return $this->stripe->accounts->retrieve($accountId);
    }

    public function deleteAccount(string $accountId): void
    {
        $this->stripe->accounts->delete($accountId);
    }

    public function createLoginLink(string $accountId): LoginLink
    {
        return $this->stripe->accounts->createLoginLink($accountId);
    }

    public function transfer(string $accountId, int|float $amount, string $currency): Transfer
    {
        return $this->stripe->transfers->create([
            'amount' => $amount,
            'currency' => $currency,
            'destination' => $accountId,
        ]);
    }
}
