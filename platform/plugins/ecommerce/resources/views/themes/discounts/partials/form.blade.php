@if ($discounts->isNotEmpty())
    {{-- Desktop Coupon Section --}}
    <div class="checkout__coupon-section d-none d-md-block">
        <div class="checkout__coupon-heading">
            <img width="32" height="32" src="{{ asset('vendor/core/plugins/ecommerce/images/coupon-code.gif') }}" alt="coupon code icon">
            {{ __('Coupon codes (:count)', ['count' => $discounts->count()]) }}
        </div>

        <div class="checkout__coupon-list">
            @foreach ($discounts as $discount)
                <div
                    @class(['checkout__coupon-item', 'active' => session()->has('applied_coupon_code') && session()->get('applied_coupon_code') === $discount->code])
                >
                    <div class="checkout__coupon-item-icon"></div>
                    <div class="checkout__coupon-item-content">
                        {!! apply_filters('checkout_discount_item_before', null, $discount) !!}

                        <div class="checkout__coupon-item-title">
                            @if ($discount->type_option !== 'shipping')
                                <h4>{{ $discount->type_option == 'percentage' ? $discount->value . '%' : format_price($discount->value) }}</h4>
                            @endif

                            @if($discount->quantity > 0)
                                <span class="checkout__coupon-item-count">
                                    ({{ __('Left :left', ['left' => $discount->left_quantity]) }})
                                </span>
                            @endif
                        </div>
                        <div class="checkout__coupon-item-description">
                            {!! BaseHelper::clean($discount->description ?: get_discount_description($discount)) !!}
                        </div>
                        <div class="checkout__coupon-item-code">
                            <span>{{ $discount->code }}</span>
                            @if (!session()->has('applied_coupon_code') || session()->get('applied_coupon_code') !== $discount->code)
                                <button type="button" data-bb-toggle="apply-coupon-code" data-discount-code="{{ $discount->code }}">
                                    {{ __('Apply') }}
                                </button>
                            @else
                                <button type="button" class="remove-coupon-code" data-url="{{ route('public.coupon.remove') }}">
                                    {{ __('Remove') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Mobile Coupon Button --}}
    <div class="checkout__coupon-mobile d-block d-md-none mb-4">
        <button
            type="button"
            class="btn btn-light border w-100 d-flex align-items-center justify-content-between p-3"
            data-bs-toggle="modal"
            data-bs-target="#mobile-coupon-modal"
        >
            <div class="d-flex align-items-center gap-2">
                <img width="24" height="24" src="{{ asset('vendor/core/plugins/ecommerce/images/coupon-code.gif') }}" alt="coupon code icon">
                <span class="text-dark fw-medium">{{ __('Select Coupon') }}</span>
                <span class="badge bg-primary">{{ $discounts->count() }}</span>
            </div>
            <x-core::icon name="ti ti-chevron-right" class="text-muted" />
        </button>
    </div>
@endif

{{-- Manual Coupon Entry Section --}}
<div
    class="checkout-discount-section"
    @if (session()->has('applied_coupon_code')) style="display: none;" @endif
>
    <a class="btn-open-coupon-form" href="#">
        {{ __('You have a coupon code?') }}
    </a>
</div>
<div
    class="coupon-wrapper mt-2"
    @if (!session()->has('applied_coupon_code')) style="display: none;" @endif
>
    @if (!session()->has('applied_coupon_code'))
        @include(EcommerceHelper::viewPath('discounts.partials.apply-coupon'))
    @else
        @include(EcommerceHelper::viewPath('discounts.partials.remove-coupon'))
    @endif
</div>

{{-- Mobile Coupon Selection Modal --}}
@if ($discounts->isNotEmpty())
    <div class="modal fade" id="mobile-coupon-modal" tabindex="-1" aria-labelledby="mobile-coupon-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="mobile-coupon-modal-label">
                        <img width="24" height="24" src="{{ asset('vendor/core/plugins/ecommerce/images/coupon-code.gif') }}" alt="coupon code icon" class="me-2">
                        {{ __('Select Coupon') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="mobile-coupon-list">
                        @foreach ($discounts as $discount)
                            <div
                                @class([
                                    'mobile-coupon-item',
                                    'border',
                                    'rounded',
                                    'mb-3',
                                    'p-3',
                                    'position-relative',
                                    'active' => session()->has('applied_coupon_code') && session()->get('applied_coupon_code') === $discount->code
                                ])
                                data-discount-code="{{ $discount->code }}"
                            >
                                @if (session()->has('applied_coupon_code') && session()->get('applied_coupon_code') === $discount->code)
                                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                            <x-core::icon name="ti ti-check" style="width: 14px; height: 14px;" />
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex align-items-start gap-3">
                                    <div class="mobile-coupon-icon bg-primary bg-opacity-10 rounded p-2 flex-shrink-0">
                                        <x-core::icon name="ti ti-discount-2" class="text-primary" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="mobile-coupon-value mb-1">
                                            @if ($discount->type_option !== 'shipping')
                                                <h6 class="mb-0 fw-bold">
                                                    {{ $discount->type_option == 'percentage' ? $discount->value . '%' : format_price($discount->value) }}
                                                </h6>
                                            @else
                                                <h6 class="mb-0 fw-bold">{{ __('Free Shipping') }}</h6>
                                            @endif

                                            @if($discount->quantity > 0)
                                                <small>
                                                    ({{ __('Left :left', ['left' => $discount->left_quantity]) }})
                                                </small>
                                            @endif
                                        </div>

                                        <div class="mobile-coupon-description mb-2">
                                            <small>
                                                {!! BaseHelper::clean($discount->description ?: get_discount_description($discount)) !!}
                                            </small>
                                        </div>

                                        <div class="mobile-coupon-code d-flex align-items-center justify-content-between">
                                            <span class="badge">{{ $discount->code }}</span>
                                            @if (!session()->has('applied_coupon_code') || session()->get('applied_coupon_code') !== $discount->code)
                                                <button type="button" class="btn" data-bb-toggle="apply-coupon-code" data-discount-code="{{ $discount->code }}">
                                                    {{ __('Apply') }}
                                                </button>
                                            @else
                                                <button type="button" class="btn remove-coupon-code" data-url="{{ route('public.coupon.remove') }}">
                                                    {{ __('Remove') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="clearfix"></div>
