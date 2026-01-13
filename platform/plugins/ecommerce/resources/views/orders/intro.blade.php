@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-plugins-ecommerce::intro
        :title="trans('plugins/ecommerce::order.manage_orders')"
        :subtitle="trans('plugins/ecommerce::order.order_intro_description')"
        :action-url="route('orders.create')"
        :action-label="trans('plugins/ecommerce::order.create_new_order')"
    >
        <x-slot:icon>
            <x-plugins-ecommerce::empty-state />
        </x-slot:icon>
    </x-plugins-ecommerce::intro>
@stop
