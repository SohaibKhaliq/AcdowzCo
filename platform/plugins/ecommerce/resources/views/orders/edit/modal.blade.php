<x-core::modal.action
    id="resend-order-confirmation-email-modal"
    :title="trans('plugins/ecommerce::order.resend_order_confirmation')"
    :description="trans('plugins/ecommerce::order.resend_order_confirmation_description', [
            'email' => $order->user->id ? $order->user->email : $order->address->email,
        ])"
    :submit-button-attrs="['id' => 'confirm-resend-confirmation-email-button']"
    :submit-button-label="trans('plugins/ecommerce::order.send')"
/>

<x-core::modal
    id="update-shipping-address-modal"
    :title="trans('plugins/ecommerce::order.update_address')"
    button-id="confirm-update-shipping-address-button"
    :button-label="trans('plugins/ecommerce::order.update')"
    size="md"
>
    @include('plugins/ecommerce::orders.shipping-address.form', [
        'address' => $order->address,
        'orderId' => $order->id,
        'url' => route($updateShippingAddressRoute, $order->address->id ?? 0),
    ])
</x-core::modal>

<x-core::modal.action
    id="cancel-order-modal"
    type="warning"
    :title="trans('plugins/ecommerce::order.cancel_order_confirmation')"
    :description="trans('plugins/ecommerce::order.cancel_order_confirmation_description')"
    :submit-button-attrs="['id' => 'confirm-cancel-order-button']"
    :submit-button-label="trans('plugins/ecommerce::order.cancel_order')"
/>
