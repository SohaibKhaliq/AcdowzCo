<x-core::button
    tag="a"
    href="{{ route('marketplace.withdrawal.invoice', $withdrawal->getKey()) }}?type=print"
    target="_blank"
    icon="ti ti-printer"
>
    {{ trans('plugins/ecommerce::order.print_invoice') }}
</x-core::button>
<x-core::button
    tag="a"
    :href="route('marketplace.withdrawal.invoice', $withdrawal->getKey())"
    target="_blank"
    icon="ti ti-download"
>
    {{ trans('plugins/ecommerce::order.download_invoice') }}
</x-core::button>
