<a
    href="{{ route('public.store', $discount->store->slug ?: '/') }}"
    class="checkout__coupon-item-store d-inline-flex align-items-center gap-1 mb-2"
>
    {{ RvMedia::image($discount->store->logo, $discount->store->name, attributes: ['width' => 28, 'height' => 28]) }}
    <span class="small fw-medium">{{ $discount->store->name }}</span>
</a>
