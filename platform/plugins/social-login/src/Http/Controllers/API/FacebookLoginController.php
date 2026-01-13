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

class FacebookLoginController extends BaseApiController
{
    public function __construct(protected SocialLoginService $socialLoginService)
    {
    }

    /**
     * Facebook login
     *
     * @group Social Login
     *
     * @bodyParam accessToken string required The Facebook access token received from Facebook Login.
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
     *   "message": "Invalid Facebook token"
     * }
     *
     * @response 400 {
     *   "error": true,
     *   "message": "Facebook authentication is not properly configured"
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

            $appId = SocialService::setting('facebook_app_id');
            $appSecret = SocialService::setting('facebook_app_secret');

            if (! $appId || ! $appSecret) {

                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Facebook authentication is not properly configured'))
                    ->toApiResponse();
            }

            $facebookUserData = $this->verifyFacebookToken($accessToken, $appId, $appSecret);

            if (! $facebookUserData) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Invalid Facebook token'))
                    ->toApiResponse();
            }

            $email = $facebookUserData['email'] ?? null;
            $facebookId = $facebookUserData['id'] ?? null;

            if (! $email || ! $facebookId) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Cannot login, no email or Facebook ID provided!'))
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
            $socialLoginUser = $this->socialLoginService->findUserByProvider('facebook', $facebookId);

            if ($socialLoginUser && ! $account) {
                $account = $socialLoginUser->user;
            }

            if (! $account) {
                $data = [
                    'name' => $facebookUserData['name'] ?? $email,
                    'email' => $email,
                    'password' => Hash::make(Str::random(36)),
                ];

                $data = apply_filters('social_login_before_saving_account', $data, (object) $facebookUserData, $providerData);

                $account = $model;
                $account->fill($data);
                $account->confirmed_at = Carbon::now();
                $account->save();

                event(new Registered($account));
            }

            $socialLoginData = $this->socialLoginService->createSocialLoginData([
                'provider' => 'facebook',
                'id' => $facebookId,
                'token' => $accessToken,
                'refresh_token' => null,
                'expires_in' => null,
                'name' => $facebookUserData['name'] ?? $account->name,
                'email' => $email,
                'avatar' => $facebookUserData['picture']['data']['url'] ?? null,
            ]);

            $this->socialLoginService->createOrUpdateSocialLogin($account, $socialLoginData);

            $token = $account->createToken('facebook-login')->plainTextToken;

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
            logger()->error('Facebook login validation error', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);

            return $this->httpResponse()
                ->setError()
                ->setMessage($e->getMessage())
                ->withInput()
                ->toApiResponse();
        } catch (Exception $e) {
            logger()->error('Facebook login error: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Facebook token invalid'))
                ->toApiResponse();
        }
    }

    protected function verifyFacebookToken(string $accessToken, string $appId, string $appSecret): ?array
    {
        try {
            $userData = $this->getUserInfoFromFacebook($accessToken);

            if ($userData) {
                if ($this->isAuthenticationToken($accessToken)) {
                    return $userData;
                }

                if ($this->validateAccessToken($accessToken, $appId, $appSecret, $userData['id'])) {
                    return $userData;
                }
            }

            return null;
        } catch (Exception $e) {
            logger()->error('Facebook token verification failed: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    protected function isAuthenticationToken(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        try {
            $header = json_decode(base64_decode(strtr($parts[0], '-_', '+/')), true);

            return isset($header['alg']) && isset($header['typ']) && $header['typ'] === 'JWT';
        } catch (Exception) {
            return false;
        }
    }

    protected function getUserInfoFromFacebook(string $accessToken): ?array
    {
        try {
            $userInfoUrl = 'https://graph.facebook.com/me';

            $userResponse = Http::timeout(10)->get($userInfoUrl, [
                'fields' => 'id,name,email,picture',
                'access_token' => $accessToken,
            ]);

            if (! $userResponse->successful()) {
                return null;
            }

            $userData = $userResponse->json();

            if (! isset($userData['id'])) {
                return null;
            }

            return $userData;

        } catch (Exception $e) {
            logger()->error('Facebook user info request failed: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return null;
        }
    }

    protected function validateAccessToken(string $accessToken, string $appId, string $appSecret, string $expectedUserId): bool
    {
        try {
            $debugTokenUrl = 'https://graph.facebook.com/debug_token';

            $debugResponse = Http::timeout(10)->get($debugTokenUrl, [
                'input_token' => $accessToken,
                'access_token' => $appId . '|' . $appSecret,
            ]);

            if (! $debugResponse->successful()) {
                return false;
            }

            $debugData = $debugResponse->json();
            $tokenData = $debugData['data'] ?? null;

            if (! $tokenData) {
                return false;
            }

            if (! ($tokenData['is_valid'] ?? false)) {
                return false;
            }

            if (($tokenData['app_id'] ?? '') !== $appId) {
                return false;
            }

            if (($tokenData['user_id'] ?? '') !== $expectedUserId) {
                return false;
            }

            return true;

        } catch (Exception $e) {
            logger()->error('Facebook access token validation failed: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }
}
