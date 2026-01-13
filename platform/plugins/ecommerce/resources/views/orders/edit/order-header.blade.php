<x-core::card.header class="justify-content-between">
    <x-core::card.title>
        {{ trans('plugins/ecommerce::order.order_information') }} {{ $order->code }}
    </x-core::card.title>

    @if ($order->completed_at)
        <x-core::badge color="info" class="d-flex align-items-center gap-1">
            <x-core::icon name="ti ti-shopping-cart-check"></x-core::icon>
            {{ trans('plugins/ecommerce::order.completed') }}
        </x-core::badge>
    @else
        <x-core::badge color="warning" class="d-flex align-items-center gap-1">
            <x-core::icon name="ti ti-shopping-cart"></x-core::icon>
            {{ trans('plugins/ecommerce::order.uncompleted') }}
        </x-core::badge>
    @endif
</x-core::card.header>
