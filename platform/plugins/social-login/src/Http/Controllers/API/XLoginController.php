<?php

namespace Botble\SocialLogin\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\SocialLogin\Facades\SocialService;
use Botble\SocialLogin\Services\SocialLoginService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class XLoginController extends BaseApiController
{
    public function __construct(protected SocialLoginService $socialLoginService)
    {
    }

    /**
     * X (Twitter) login
     *
     * @group Social Login
     *
     * @bodyParam accessToken string required The X (Twitter) access token received from X OAuth.
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
     *   "message": "Invalid X (Twitter) token"
     * }
     *
     * @response 400 {
     *   "error": true,
     *   "message": "X (Twitter) authentication is not properly configured"
     * }
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'accessToken' => ['required', 'string'],
                'guard' => ['string', 'nullable'],
            ]);

            $accessToken = $request->input('accessToken');
            $guard = $request->input('guard', 'web');

            $clientId = SocialService::setting('x_app_id');
            $clientSecret = SocialService::setting('x_app_secret');

            if (! $clientId || ! $clientSecret) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('X (Twitter) authentication is not properly configured'))
                    ->toApiResponse();
            }

            $xUserData = $this->verifyXToken($accessToken);

            if (! $xUserData) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Invalid X (Twitter) token'))
                    ->toApiResponse();
            }

            $email = $xUserData['email'] ?? null;
            $xId = $xUserData['id'] ?? null;

            if (! $email || ! $xId) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Cannot login, no email or X ID provided!'))
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
            $socialLoginUser = $this->socialLoginService->findUserByProvider('x', $xId);

            if ($socialLoginUser && ! $account) {
                $account = $socialLoginUser->user;
            }

            if (! $account) {
                $data = [
                    'name' => $xUserData['name'] ?? $xUserData['username'] ?? $email,
                    'email' => $email,
                    'password' => Hash::make(Str::random(36)),
                ];

                $data = apply_filters('social_login_before_saving_account', $data, (object) $xUserData, $providerData);

                $account = $model;
                $account->fill($data);
                $account->confirmed_at = Carbon::now();
                $account->save();

                event(new Registered($account));
            }

            $socialLoginData = $this->socialLoginService->createSocialLoginData([
                'provider' => 'x',
                'id' => $xId,
                'token' => $accessToken,
                'refresh_token' => null,
                'expires_in' => null,
                'name' => $xUserData['name'] ?? $xUserData['username'] ?? $account->name,
                'email' => $email,
                'avatar' => $xUserData['profile_image_url'] ?? null,
            ]);

            $this->socialLoginService->createOrUpdateSocialLogin($account, $socialLoginData);

            $token = $account->createToken('x-login')->plainTextToken;

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
            logger()->error('X login validation error', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            return $this->httpResponse()
                ->setError()
                ->setMessage($e->getMessage())
                ->withInput()
                ->toApiResponse();
        } catch (Exception $e) {
            logger()->error('X login error: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->httpResponse()
                ->setError()
                ->setMessage(__('X (Twitter) token invalid'))
                ->toApiResponse();
        }
    }

    protected function verifyXToken(string $accessToken): ?array
    {
        try {
            $userData = $this->getUserInfoFromX($accessToken);

            if ($userData) {
                return $userData;
            }

            return null;

        } catch (Exception $e) {
            logger()->error('X token verification failed: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    protected function getUserInfoFromX(string $accessToken): ?array
    {
        try {
            $userInfoUrl = 'https://api.x.com/2/users/me';

            $userResponse = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])
                ->get($userInfoUrl, [
                    'user.fields' => 'id,name,username,email,profile_image_url',
                ]);

            if (! $userResponse->successful()) {
                return null;
            }

            $responseData = $userResponse->json();
            $userData = $responseData['data'] ?? null;

            if (! $userData) {
                return null;
            }

            if (! isset($userData['id'])) {
                return null;
            }

            return $userData;

        } catch (Exception $e) {
            logger()->error('X user info request failed: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return null;
        }
    }
}
