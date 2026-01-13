@extends('plugins/ecommerce::orders.master')

@section('title', __('Order successfully at :site_title', ['site_title' => Theme::getSiteTitle()]))

@section('content')
    <div class="row">
        <div class="col-lg-7 col-md-6 col-12">
            @include('plugins/ecommerce::orders.partials.logo')

            <div class="thank-you">
                <x-core::icon name="ti ti-circle-check-filled" />

                <div class="d-inline-block">
                    <h3 class="thank-you-sentence">
                        {{ __('Your order is successfully placed') }}
                    </h3>
                    <p>{{ __('Thank you for purchasing our products!') }}</p>
                </div>
            </div>

            @include('plugins/ecommerce::orders.thank-you.customer-info', [
                'order' => $orders,
                'isShowShipping' => false,
            ])

            <a
                class="btn payment-checkout-btn d-none d-md-inline-block mb-5"
                href="{{ BaseHelper::getHomepageUrl() }}"
            > {{ __('Continue shopping') }} </a>
        </div>

        <div class="col-lg-5 col-md-6 mt-3 mt-md-0 mb-3 mb-md-5">
            @php
                $isUnifiedShipping = ! MarketplaceHelper::isChargeShippingPerVendor();
                $totalShippingAmount = $orders->sum('shipping_amount');
                $hasShipping = $orders->filter(fn ($order) => $order->shipment->id)->count() > 0;
            @endphp

            @foreach ($orders as $order)
                <div class="bg-light p-2 p-md-3 pt-0">
                    @if ($isUnifiedShipping)
                        {{-- For unified shipping, show products only without individual shipping breakdown --}}
                        @include('plugins/ecommerce::orders.thank-you.order-info', ['isShowTotalInfo' => false])

                        {{-- Show simplified order totals for individual orders --}}
                        <hr class="border-dark-subtle" />

                        @if ($order->sub_total != $order->amount)
                            @include('plugins/ecommerce::orders.thank-you.total-row', [
                                'label' => __('Subtotal'),
                                'value' => format_price($order->sub_total),
                            ])
                        @endif

                        @if (EcommerceHelper::isTaxEnabled() && (float) $order->tax_amount)
                            @include('plugins/ecommerce::orders.thank-you.total-row', [
                                'label' => __('Tax'),
                                'value' => format_price($order->tax_amount),
                            ])
                        @endif

                        @if ((float) $order->discount_amount)
                            @include('plugins/ecommerce::orders.thank-you.total-row', [
                                'label' => __('Discount'),
                                'value' => format_price($order->discount_amount) .
                                    ($order->coupon_code ? ' <small>(' . __('Using coupon code') . ': <strong>' . $order->coupon_code . '</strong>)</small>' : ''),
                            ])
                        @endif

                        @if ((float) $order->payment_fee)
                            @include('plugins/ecommerce::orders.thank-you.total-row', [
                                'label' => __('plugins/payment::payment.payment_fee'),
                                'value' => format_price($order->payment_fee),
                            ])
                        @endif

                        <hr class="border-dark-subtle" />

                        <div class="row">
                            <div class="col-6">
                                <p>{{ __('Order Total') }}:</p>
                            </div>
                            <div class="col-6 float-end">
                                <p class="total-text raw-total-text"> {{ format_price($order->amount) }} </p>
                            </div>
                        </div>
                    @else
                        {{-- For per-vendor shipping, show normal order info with shipping --}}
                        @include('plugins/ecommerce::orders.thank-you.order-info', ['isShowTotalInfo' => true])
                    @endif
                </div>

                @if (! $loop->last)
                    <hr class="border-dark-subtle" />
                @endif
            @endforeach

            @if (count($orders) > 1)
                <hr class="border-dark-subtle" />
                <!-- total info -->
                <div class="bg-light p-2 p-md-3">
                    <div class="row total-price">
                        <div class="col-6">
                            <p>{{ __('Sub amount') }}:</p>
                        </div>
                        <div class="col-6">
                            <p class="text-end">{{ format_price($orders->sum('sub_total')) }}</p>
                        </div>
                    </div>

                    @if ($hasShipping && $totalShippingAmount > 0)
                        <div class="row total-price">
                            <div class="col-6">
                                <p>{{ __('Shipping fee') }}:</p>
                            </div>
                            <div class="col-6">
                                <p class="text-end">{{ format_price($totalShippingAmount) }} </p>
                            </div>
                        </div>
                    @endif

                    @if ($orders->sum('discount_amount'))
                        <div class="row total-price">
                            <div class="col-6">
                                <p>{{ __('Discount') }}:</p>
                            </div>
                            <div class="col-6">
                                <p class="text-end">{{ format_price($orders->sum('discount_amount')) }} </p>
                            </div>
                        </div>
                    @endif

                    @if (EcommerceHelper::isTaxEnabled())
                        <div class="row total-price">
                            <div class="col-6">
                                <p>{{ __('Tax') }}:</p>
                            </div>
                            <div class="col-6">
                                <p class="text-end">{{ format_price($orders->sum('tax_amount')) }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($orders->sum('payment_fee'))
                        <div class="row total-price">
                            <div class="col-6">
                                <p>{{ __('Payment Fee') }}:</p>
                            </div>
                            <div class="col-6">
                                <p class="text-end">{{ format_price($orders->sum('payment_fee')) }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="row total-price">
                        <div class="col-6">
                            <p>{{ __('Total amount') }}:</p>
                        </div>
                        <div class="col-6">
                            <p class="total-text raw-total-text text-end">
                                {{ format_price($orders->sum('amount')) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Continue shopping button for mobile, positioned at the bottom --}}
    <div class="d-md-none mt-4 mb-4">
        <a class="btn payment-checkout-btn w-100 mb-5" href="{{ BaseHelper::getHomepageUrl() }}">
            {{ __('Continue shopping') }}
        </a>
    </div>
@stop
