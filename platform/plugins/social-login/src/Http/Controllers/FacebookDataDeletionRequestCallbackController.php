<?php

namespace Botble\SocialLogin\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\SocialLogin\Supports\FacebookDataDeletionSignedRequestParser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FacebookDataDeletionRequestCallbackController extends BaseController
{
    public function handle(
        Request $request,
        FacebookDataDeletionSignedRequestParser $signedRequestParser
    ) {
        if (! setting('social_login_enable') || ! setting('social_login_facebook_enable')) {
            return response()->json([
                'error' => true,
                'message' => 'Facebook login is currently disabled.',
                'data' => null,
            ], 503);
        }

        if ($request->isMethod('GET')) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Facebook Data Deletion Callback URL is active',
            ]);
        }

        return $this->store($request, $signedRequestParser);
    }

    public function store(
        Request $request,
        FacebookDataDeletionSignedRequestParser $signedRequestParser
    ) {
        if ($request->has('signed_request')) {
            $data = $signedRequestParser->parse($request->input('signed_request'));

            if (! $data) {
                return response()->json([
                    'error' => 'Invalid signed request',
                ], 400);
            }
        }

        $confirmationCode = Str::uuid()->toString();

        return response()->json([
            'url' => route('facebook-deletion-status', ['id' => $confirmationCode]),
            'confirmation_code' => $confirmationCode,
        ]);
    }

    public function show(string $id)
    {
        abort_unless(Str::isUuid($id), 404);

        return response()->json([
            'status' => 'pending',
            'message' => 'Your data deletion request is pending. We will notify you once it is completed.',
        ]);
    }
}
