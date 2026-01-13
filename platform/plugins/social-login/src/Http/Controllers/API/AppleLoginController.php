<?php

namespace Botble\SocialLogin\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Base\Facades\BaseHelper;
use Botble\SocialLogin\Facades\SocialService;
use Botble\SocialLogin\Services\AppleJwtService;
use Botble\SocialLogin\Services\SocialLoginService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AppleLoginController extends BaseApiController
{
    public function __construct(
        protected SocialLoginService $socialLoginService,
        protected AppleJwtService $appleJwtService
    ) {
    }

    /**
     * Apple login
     *
     * @group Social Login
     *
     * @bodyParam identityToken string required The Apple identity token received from Apple Sign-In.
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
     *   "message": "Invalid Apple token"
     * }
     *
     * @response 400 {
     *   "error": true,
     *   "message": "Cannot login, no email or Apple ID provided!"
     * }
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'identityToken' => ['required', 'string'],
                'guard' => ['string', 'nullable'],
            ]);

            $identityToken = $request->input('identityToken');
            $guard = $request->input('guard', 'web');

            $appleUserData = $this->appleJwtService->verifyToken($identityToken);

            if (! $appleUserData) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Invalid Apple token'))
                    ->toApiResponse();
            }

            $email = $appleUserData['email'] ?? null;
            $appleId = $appleUserData['sub'] ?? null;

            if (! $email || ! $appleId) {
                return $this->httpResponse()
                    ->setError()
                    ->setMessage(__('Cannot login, no email or Apple ID provided!'))
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

            $socialLoginUser = $this->socialLoginService->findUserByProvider('apple', $appleId);

            if ($socialLoginUser && ! $account) {
                $account = $socialLoginUser->user;
            }

            if (! $account) {
                $data = [
                    'name' => $appleUserData['name'] ?? $email,
                    'email' => $email,
                    'password' => Hash::make(Str::random(36)),
                ];

                $data = apply_filters('social_login_before_saving_account', $data, (object) $appleUserData, $providerData);

                $account = $model;
                $account->fill($data);
                $account->confirmed_at = Carbon::now();
                $account->save();

                event(new Registered($account));
            }

            $socialLoginData = $this->socialLoginService->createSocialLoginData([
                'provider' => 'apple',
                'id' => $appleId,
                'token' => $identityToken,
                'refresh_token' => null,
                'expires_in' => null,
                'name' => $appleUserData['name'] ?? $account->name,
                'email' => $email,
                'avatar' => null,
            ]);

            // Use the new method that handles duplicates properly
            $this->socialLoginService->createOrUpdateSocialLogin($account, $socialLoginData);

            $token = $account->createToken('apple-login')->plainTextToken;

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
            return $this->httpResponse()
                ->setError()
                ->setMessage($e->getMessage())
                ->withInput()
                ->toApiResponse();
        } catch (Exception $e) {
            BaseHelper::logError($e);

            return $this->httpResponse()
                ->setError()
                ->setMessage(__('Apple token invalid'))
                ->toApiResponse();
        }
    }
}
