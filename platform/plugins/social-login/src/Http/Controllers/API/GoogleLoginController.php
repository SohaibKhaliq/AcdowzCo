<?php

namespace Botble\SocialLogin\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\SocialLogin\Facades\SocialService;
use Botble\SocialLogin\Services\SocialLoginService;
use Carbon\Carbon;
use Exception;
use Google_Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GoogleLoginController extends BaseApiController
{
    public function __construct(protected SocialLoginService $socialLoginService)
    {
    }

    /**
     * Google login
     *
     * @group Social Login
     *
     * @bodyParam identityToken string required The Google identity token received from Google Sign-In.
     * @bodyParam guard string optional The guard to use for authentication (default: web).
     *
     * @response 200 {
     *   "error": false,
     *   "data": {
     *     "token": "1|abc123def456...",
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com"
     *     }
     *   },
     *   "message": "Login successful"
     * }
     *
     * @response 400 {
     *   "error": true,
     *   "message": "Invalid Google token"
     * }
     *
     * @response 400 {
     *   "error": true,
     *   "message": "Google authentication is not properly configured"
     * }
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'identityToken' => ['required', 'string'],
                'guard' => ['string', 'nullable'],
            ]);

            $identityToken = $request->input('identityToken');
            $guard = $request->input('guard', 'web');

            $clientId = SocialService::setting('google_app_id');
            $clientSecret = SocialService::setting('google_app_secret');

            if (! $clientId || ! $clientSecret) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Google authentication is not properly configured'))
                    ->toApiResponse();
            }

            $googleUserData = $this->verifyGoogleToken($identityToken, $clientId);

            if (! $googleUserData) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Invalid Google token'))
                    ->toApiResponse();
            }

            $email = $googleUserData['email'] ?? null;
            $googleId = $googleUserData['sub'] ?? null;

            if (! $email || ! $googleId) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Cannot login, no email or Google ID provided!'))
                    ->toApiResponse();
            }

            $providerData = SocialService::supportedModules()[$guard] ?? null;

            if (! $providerData) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Invalid guard configuration'))
                    ->toApiResponse();
            }

            $model = new $providerData['model']();

            $account = $this->socialLoginService->findUserByEmail($email, $model::class);
            $socialLoginUser = $this->socialLoginService->findUserByProvider('google', $googleId);

            if ($socialLoginUser && ! $account) {
                $account = $socialLoginUser->user;
            }

            if (! $account) {
                $data = [
                    'name' => $googleUserData['name'] ?? $googleUserData['given_name'] ?? $email,
                    'email' => $email,
                    'password' => Hash::make(Str::random(36)),
                ];

                $data = apply_filters('social_login_before_saving_account', $data, (object) $googleUserData, $providerData);

                $account = $model;
                $account->fill($data);
                $account->confirmed_at = Carbon::now();
                $account->save();

                event(new Registered($account));
            }

            $socialLoginData = $this->socialLoginService->createSocialLoginData([
                'provider' => 'google',
                'id' => $googleId,
                'token' => $identityToken,
                'refresh_token' => null,
                'expires_in' => null,
                'name' => $googleUserData['name'] ?? $googleUserData['given_name'] ?? $account->name,
                'email' => $email,
                'avatar' => $googleUserData['picture'] ?? null,
            ]);

            // Use the new method that handles duplicates properly
            $this->socialLoginService->createOrUpdateSocialLogin($account, $socialLoginData);

            $token = $account->createToken('google-login')->plainTextToken;

            return $this->httpResponse()
                ->setData([
                    'token' => $token,
                    'user' => [
                        'id' => $account->getKey(),
                        'name' => $account->name,
                        'email' => $account->email,
                    ],
                ])
                ->setMessage(__('Login successful'))
                ->toApiResponse();

        } catch (ValidationException $e) {
            logger()->error('Google login validation error', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            return $this->httpResponse()
                ->setError()
                ->setMessage($e->getMessage())
                ->withInput()
                ->toApiResponse();
        } catch (Exception $e) {
            logger()->error('Google login error: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Google token invalid'))
                ->toApiResponse();
        }
    }

    protected function verifyGoogleToken(string $identityToken, string $clientId): ?array
    {
        try {
            $client = new Google_Client(['client_id' => $clientId]);

            $payload = $client->verifyIdToken($identityToken);

            if (! $payload) {
                return null;
            }

            return $payload;

        } catch (Exception $e) {
            logger()->error('Google token verification failed: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}
