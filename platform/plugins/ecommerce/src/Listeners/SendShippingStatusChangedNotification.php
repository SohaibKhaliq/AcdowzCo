<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Base\Events\AdminNotificationEvent;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Supports\AdminNotificationItem;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Events\ShippingStatusChanged;
use Botble\Ecommerce\Facades\OrderHelper;

class SendShippingStatusChangedNotification
{
    public function handle(ShippingStatusChanged $event): void
    {
        $order = $event->shipment->order;
        $shipment = $event->shipment;

        $emailVariables = OrderHelper::getEmailVariables($order);

        $emailVariables['shipping_company_name'] = $shipment->shipping_company_name;
        $emailVariables['tracking_id'] = $shipment->tracking_id;
        $emailVariables['tracking_link'] = $shipment->tracking_link;

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
            ->setVariableValues($emailVariables);

        if ($event->shipment->status == ShippingStatusEnum::DELIVERING) {
            $mailer->sendUsingTemplate('customer_delivery_order', $order->user->email ?: $order->address->email);
        }

        if ($event->shipment->status == ShippingStatusEnum::DELIVERED) {
            $mailer->sendUsingTemplate('customer_order_delivered', $order->user->email ?: $order->address->email);

            event(
                new AdminNotificationEvent(
                    AdminNotificationItem::make()
                        ->title(trans('plugins/ecommerce::order.order_completed_notifications.order_completed_with_code', ['code' => $order->code]))
                        ->description(
                            trans('plugins/ecommerce::order.order_completed_notifications.description', [
                                'order' => $order->code,
                            ])
                        )
                        ->action(
                            trans('plugins/ecommerce::order.new_order_notifications.view'),
                            route('orders.edit', $order->id)
                        )
                )
            );
        } else {
            event(
                new AdminNotificationEvent(
                    AdminNotificationItem::make()
                        ->title(
                            trans(
                                'plugins/ecommerce::order.update_shipping_status_notifications.update_shipping_status_with_code',
                                ['code' => $order->code]
                            )
                        )
                        ->description(
                            trans('plugins/ecommerce::order.update_shipping_status_notifications.description', [
                                'order' => $order->code,
                                'description' => $event->previousShipment
                                    ? trans('plugins/ecommerce::order.update_shipping_status_notifications.changed_from_to', [
                                        'old_status' => ShippingStatusEnum::getLabel($event->previousShipment['status']),
                                        'new_status' => ShippingStatusEnum::getLabel($event->shipment->status),
                                      ])
                                    : trans('plugins/ecommerce::order.update_shipping_status_notifications.changed_to', [
                                        'status' => ShippingStatusEnum::getLabel($event->shipment->status),
                                      ]),
                            ])
                        )
                        ->action(
                            trans('plugins/ecommerce::order.new_order_notifications.view'),
                            route('orders.edit', $order->id)
                        )
                )
            );
        }
    }
}
