@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Orders'))

@section('content')
    @if($orders->isNotEmpty())
        <div class="customer-list-order">
            <!-- Orders Grid -->
            <div class="bb-customer-card-list order-cards">
                @foreach ($orders as $order)
                    <div class="bb-customer-card order-card">
                        <!-- Order Header -->
                        <div class="bb-customer-card-header">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="bb-customer-card-title h6 mb-1">
                                        {{ __('Order :code', ['code' => $order->code]) }}
                                    </h3>
                                    <div class="bb-customer-card-status">
                                        {!! BaseHelper::clean($order->status->toHtml()) !!}
                                    </div>
                                    <p class="text-muted small mb-0">
                                        {{ $order->created_at->translatedFormat('M d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="bb-customer-card-body">
                            <div class="bb-customer-card-info">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <span class="label text-muted small">{{ __('Total Amount') }}</span>
                                            <span class="value fw-semibold d-block">{{ $order->amount_format }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-item">
                                            <span class="label text-muted small">{{ __('Items') }}</span>
                                            <span class="value fw-semibold d-block">{{ $order->products_count }} {{ __('item(s)') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="info-item">
                                            <span class="label text-muted small">{{ __('Payment Method') }}</span>
                                            <span class="value fw-semibold d-block">
                                                @if(is_plugin_active('payment') && $order->payment->id && $order->payment->payment_channel->label())
                                                    {{ $order->payment->payment_channel->label() }}
                                                @else
                                                    {{ __('Not specified') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="bb-customer-card-footer">
                            <div class="d-flex justify-content-end">
                                <a
                                    class="btn btn-primary btn-sm"
                                    href="{{ route('customer.orders.view', $order->id) }}"
                                >
                                    <x-core::icon name="ti ti-eye" class="me-1" />
                                    {{ __('View Details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {!! $orders->links() !!}
                </div>
            @endif
        </div>
    @else
        @include(EcommerceHelper::viewPath('customers.partials.empty-state'), [
            'title' => __('No orders yet!'),
            'subtitle' => __('You have not placed any orders yet.'),
            'actionUrl' => route('public.products'),
            'actionLabel' => __('Start shopping now'),
        ])
    @endif
@stop
