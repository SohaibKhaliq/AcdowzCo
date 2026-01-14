<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Enums\OrderHistoryActionEnum;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\Shipment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends BaseController
{
    public function updateTrackingId(Request $request, Order $order)
    {
        if (! Auth::user()->hasPermission('orders.edit')) {
            abort(403);
        }

        $request->validate([
            'tracking_id' => 'nullable|string|max:191',
        ]);

        $trackingId = $request->input('tracking_id');
        $oldTrackingId = $order->tracking_id;

        try {
            // Update tracking ID on order
            $order->update([
                'tracking_id' => $trackingId,
            ]);

            // Also update shipment if exists
            if ($order->shipment && $order->shipment->id) {
                $order->shipment->update([
                    'tracking_id' => $trackingId,
                ]);
            }

            // Create order history entry
            $historyData = [
                'action' => OrderHistoryActionEnum::OTHER,
                'description' => $oldTrackingId 
                    ? trans('plugins/ecommerce::order.tracking_id_updated_from_to', [
                        'old' => $oldTrackingId,
                        'new' => $trackingId ?: 'removed'
                    ])
                    : trans('plugins/ecommerce::order.tracking_id_added', ['tracking_id' => $trackingId]),
                'user_id' => Auth::id(),
            ];

            $order->histories()->create($historyData);

            // Send notification email to customer if tracking ID is added
            if ($trackingId && $order->user && $order->user->email) {
                try {
                    EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
                        ->setVariableValues([
                            'order_id' => $order->code,
                            'order_token' => $order->token,
                            'tracking_id' => $trackingId,
                            'customer_name' => $order->address->name,
                            'customer_email' => $order->user->email,
                        ])
                        ->sendUsingTemplate('order-tracking-updated', $order->user->email);
                } catch (Exception $e) {
                    // Log but don't fail if email sending fails
                    logger()->error('Failed to send tracking email: ' . $e->getMessage());
                }
            }

            event(new UpdatedContentEvent(ORDER_MODULE_SCREEN_NAME, $request, $order));

            return $this
                ->httpResponse()
                ->setMessage(trans('plugins/ecommerce::order.tracking_id_updated_successfully'));
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
