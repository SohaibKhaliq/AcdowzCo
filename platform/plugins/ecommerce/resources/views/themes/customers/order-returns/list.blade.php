@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Order Return Requests'))

@section('content')
    @if($requests->isNotEmpty())
        <div class="customer-list-order">
            <!-- Return Requests Grid -->
            <div class="bb-customer-card-list order-return-cards">
                @foreach ($requests as $item)
                    <div class="bb-customer-card order-return-card">
                        <!-- Return Request Header -->
                        <div class="bb-customer-card-header">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="bb-customer-card-title h6 mb-1">
                                        {{ __('Return Request :code', ['code' => $item->code]) }}
                                    </h3>
                                    <p class="text-muted small mb-0">
                                        {{ $item->created_at->translatedFormat('M d, Y \a\t g:i A') }}
                                    </p>
                                    <div class="bb-customer-card-status">
                                        {!! BaseHelper::clean($item->return_status->toHtml()) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return Request Content -->
                        <div class="bb-customer-card-body">
                            <div class="bb-customer-card-info">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="label">
                                                <x-core::icon name="ti ti-shopping-cart" class="me-1" />
                                                {{ __('Original Order') }}
                                            </span>
                                            <span class="value">
                                                <a
                                                    href="{{ route('customer.orders.view', $item->order_id) }}"
                                                    class="text-decoration-none fw-medium"
                                                    title="{{ __('Click to view order details') }}"
                                                >
                                                    {{ $item->order->code }}
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="label">
                                                <x-core::icon name="ti ti-package" class="me-1" />
                                                {{ __('Items Count') }}
                                            </span>
                                            <span class="value">
                                                {{ trans_choice('{1} :count item|[2,*] :count items', $item->items_count, ['count' => $item->items_count]) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return Request Actions -->
                        <div class="bb-customer-card-footer">
                            <div class="d-flex justify-content-end">
                                <a
                                    class="btn btn-primary btn-sm"
                                    href="{{ route('customer.order_returns.detail', $item->id) }}"
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
            @if($requests->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {!! $requests->links() !!}
                </div>
            @endif
        </div>
    @else
        @include(EcommerceHelper::viewPath('customers.partials.empty-state'), [
            'title' => __('No order return requests yet!'),
            'subtitle' => __('You have not placed any order return requests yet.'),
        ])
    @endif
@stop
