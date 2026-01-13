<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Fronts\UploadProofRequest;
use Botble\Ecommerce\Models\Order;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class GuestPaymentProofController extends BaseController
{
    protected function validateOrderToken(string $token): ?Order
    {
        // Validate token format (should be a valid UUID or similar)
        if (! $token || strlen($token) < 20) {
            abort(404);
        }

        // Rate limiting by IP to prevent brute force attempts
        $key = 'payment-proof-access:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            abort(Response::HTTP_TOO_MANY_REQUESTS, 'Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60); // 10 attempts per minute

        // Find order by token
        $order = Order::query()
            ->where('token', $token)
            ->first();

        if (! $order) {
            abort(404);
        }

        return $order;
    }
    public function upload(string $token, UploadProofRequest $request)
    {
        if (! EcommerceHelper::isPaymentProofEnabled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Payment proof upload is currently disabled.'));
        }

        if (! EcommerceHelper::isGuestPaymentProofEnabled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Guest payment proof upload is currently disabled.'));
        }

        // Validate order token with rate limiting
        $order = $this->validateOrderToken($token);

        // Check if order can accept payment proof
        if (! $order->canBeCanceled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('This order cannot accept payment proof uploads at this time.'));
        }

        $storage = Storage::disk('local');

        if ($order->proof_file) {
            $storage->delete($order->proof_file);
        }

        if (! $storage->exists('proofs')) {
            $storage->makeDirectory('proofs');
        }

        $file = $request->file('file');

        $proofFilePath = $storage->putFileAs('proofs', $file, sprintf('%s-%s', $order->getKey(), $file->getClientOriginalName()));

        $order->update([
            'proof_file' => $proofFilePath,
        ]);

        // Prepare email variables
        $emailVariables = [
            'customer_name' => $order->user->name ?? $order->address->name,
            'customer_email' => $order->user->email ?? $order->address->email,
            'order_id' => get_order_code($order->getKey()),
            'order_link' => route('orders.edit', $order),
        ];

        if (is_plugin_active('payment') && $order->payment->id) {
            $emailVariables['payment_link'] = route('payment.show', $order->payment->id);
        }

        EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
            ->setVariableValues($emailVariables)
            ->sendUsingTemplate('payment-proof-upload-notification', args: [
                'attachments' => [$storage->path($proofFilePath)],
            ]);

        return $this
            ->httpResponse()
            ->setMessage(__('Uploaded proof successfully'));
    }

    public function download(string $token)
    {
        // Validate order token with rate limiting
        $order = $this->validateOrderToken($token);

        $storage = Storage::disk('local');

        abort_unless($order->proof_file && $storage->exists($order->proof_file), 404);

        return $storage->download($order->proof_file);
    }
}
