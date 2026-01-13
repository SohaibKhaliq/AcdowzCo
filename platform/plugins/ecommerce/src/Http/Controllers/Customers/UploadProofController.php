<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Fronts\UploadProofRequest;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Order;
use Illuminate\Support\Facades\Storage;

class UploadProofController extends BaseController
{
    public function upload(int|string $id, UploadProofRequest $request)
    {
        if (! EcommerceHelper::isPaymentProofEnabled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Payment proof upload is currently disabled.'));
        }

        /**
         * @var Customer $customer
         */
        $customer = $request->user('customer');

        $order = Order::query()
            ->where('user_id', $customer->getKey())
            ->find($id);

        if (! $order) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('You do not have permission to upload payment proof for this order.'));
        }

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

        $emailVariables = [
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
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

    public function download(int|string $id)
    {
        $order = Order::query()
            ->where('user_id', auth('customer')->id())
            ->find($id);

        if (! $order) {
            return redirect()->back()->with('error', __('You do not have permission to download payment proof for this order.'));
        }

        $storage = Storage::disk('local');

        if (! $order->proof_file || ! $storage->exists($order->proof_file)) {
            return redirect()->back()->with('error', __('Payment proof file not found.'));
        }

        return $storage->download($order->proof_file);
    }
}
