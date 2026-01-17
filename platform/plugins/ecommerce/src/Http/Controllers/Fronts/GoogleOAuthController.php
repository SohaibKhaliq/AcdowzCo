<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Events\CustomerRegistered;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Exception;
use Google_Client;
use Google_Service_Oauth2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleOAuthController extends BaseController
{
    protected Google_Client $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(route('customer.oauth.google.callback'));
        $this->client->addScope(['email', 'profile']);
    }

    public function redirectToGoogle(): mixed
    {
        if (!$this->isGoogleOAuthEnabled()) {
            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Google OAuth is not enabled'));
        }

        $authUrl = $this->client->createAuthUrl();

        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request): BaseHttpResponse
    {
        if (!$this->isGoogleOAuthEnabled()) {
            return $this->httpResponse()
                ->setError()
                ->setNextUrl(route('customer.login'))
                ->setMessage(__('Google OAuth is not enabled'));
        }

        $authCode = $request->input('code');

        if (!$authCode) {
            return $this->httpResponse()
                ->setError()
                ->setNextUrl(route('customer.login'))
                ->setMessage(__('Authorization code not provided'));
        }

        try {
            // Exchange authorization code for access token
            $token = $this->client->fetchAccessTokenWithAuthCode($authCode);

            if (isset($token['error'])) {
                throw new Exception($token['error_description']);
            }

            $this->client->setAccessToken($token);

            // Get user info from Google
            $oauth2 = new Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            // Find or create customer
            $customer = $this->findOrCreateCustomer($userInfo);

            // Log in the customer
            Auth::guard('customer')->login($customer, true);

            event(new CustomerRegistered($customer));

            return $this->httpResponse()
                ->setNextUrl($request->input('intended', route('customer.overview')))
                ->setMessage(__('Successfully logged in with Google'));
        } catch (Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());

            return $this->httpResponse()
                ->setError()
                ->setNextUrl(route('customer.login'))
                ->setMessage(__('Failed to authenticate with Google. Please try again.'));
        }
    }

    protected function findOrCreateCustomer($userInfo): Customer
    {
        // Try to find existing customer by OAuth UID
        $customer = Customer::where('oauth_provider', 'google')
            ->where('oauth_uid', $userInfo->id)
            ->first();

        if ($customer) {
            // Update existing customer info if needed
            $customer->update([
                'name' => $userInfo->name,
                'email' => $userInfo->email,
                'avatar' => $userInfo->picture,
                'email_verified_at' => now(),
            ]);

            return $customer;
        }

        // Check if customer exists with same email
        $existingCustomer = Customer::where('email', $userInfo->email)->first();

        if ($existingCustomer) {
            // Link OAuth to existing customer
            $existingCustomer->update([
                'oauth_provider' => 'google',
                'oauth_uid' => $userInfo->id,
                'name' => $userInfo->name,
                'avatar' => $userInfo->picture,
                'email_verified_at' => now(),
            ]);

            return $existingCustomer;
        }

        // Create new customer
        return Customer::create([
            'name' => $userInfo->name,
            'email' => $userInfo->email,
            'avatar' => $userInfo->picture,
            'oauth_provider' => 'google',
            'oauth_uid' => $userInfo->id,
            'password' => bcrypt(Str::random(32)), // Random password since they'll use OAuth
            'email_verified_at' => now(),
            'reseller_id' => 'RSL' . strtoupper(Str::random(10)),
        ]);
    }

    protected function isGoogleOAuthEnabled(): bool
    {
        return config('services.google.client_id') &&
            config('services.google.client_secret') &&
            EcommerceHelper::getSetting('google_oauth_enabled', false);
    }
}
