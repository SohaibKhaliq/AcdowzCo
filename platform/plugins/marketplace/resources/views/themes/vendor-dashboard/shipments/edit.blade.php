@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))
@section('content')
    <div class="row">
        <div class="col-md-9">
            @include('plugins/ecommerce::shipments.notification')

            @include('plugins/ecommerce::shipments.products', [
                'productEditRouteName' => 'marketplace.vendor.products.edit',
                'orderEditRouteName' => 'marketplace.vendor.orders.edit',
            ])

            @include('plugins/ecommerce::shipments.form', [
                'updateStatusRouteName' => 'marketplace.vendor.orders.update-shipping-status',
                'updateCodStatusRouteName' => 'marketplace.vendor.shipments.update-cod-status',
            ])

            @include('plugins/ecommerce::shipments.histories')
        </div>

        <div class="col-md-3">
            @include('plugins/ecommerce::shipments.information', [
                'orderEditRouteName' => 'marketplace.vendor.orders.edit',
            ])
        </div>
    </div>
@stop

@push('footer')
    @if(!$shipment->isCancelled)
        @include('plugins/ecommerce::shipments.partials.update-cod-status', [
            'shipment' => $shipment,
            'updateCodStatusUrl' => route('marketplace.vendor.shipments.update-cod-status', $shipment->id),
        ])

        @if (! EcommerceHelper::isDisabledPhysicalProduct() && $shipment && $shipment->id)
            <x-core::modal
                id="update-shipping-status-modal"
                :title="trans('plugins/ecommerce::shipping.update_shipping_status')"
                button-id="confirm-update-shipping-status-button"
                :button-label="trans('plugins/ecommerce::order.update')"
            >
                @include(MarketplaceHelper::viewPath('vendor-dashboard.orders.shipping-status-modal'), [
                    'shipment' => $shipment,
                    'url' => route('marketplace.vendor.orders.update-shipping-status', $shipment->id),
                ])
            </x-core::modal>
        @endif
    @endif
@endpush
