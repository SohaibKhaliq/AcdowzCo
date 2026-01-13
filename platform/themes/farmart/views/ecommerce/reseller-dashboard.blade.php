@php
    Theme::set('pageName', __('Reseller Dashboard'));
@endphp

{!! Theme::partial('page-header') !!}

<div class="container">
    <div class="row">
        @include('plugins/ecommerce::themes.customers.sidebar')
        
        <div class="col-lg-9 col-md-8 col-12">
            <div class="dashboard-wrapper">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">{{ __('Reseller Mode') }}</h5>
                                        <p class="mb-0 text-muted">{{ __('Your Reseller ID:') }} <code>{{ $customer->reseller_id }}</code></p>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-{{ $customer->is_reseller_active ? 'danger' : 'success' }}" id="toggle-reseller-status">
                                            {{ $customer->is_reseller_active ? __('Disable') : __('Enable') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="mb-1">{{ $stats['total_clicks'] }}</h3>
                                <p class="mb-0 text-muted">{{ __('Total Clicks') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="mb-1">{{ $stats['total_orders'] }}</h3>
                                <p class="mb-0 text-muted">{{ __('Orders') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="mb-1">{{ format_price($stats['pending_commission']) }}</h3>
                                <p class="mb-0 text-muted">{{ __('Pending') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="mb-1">{{ format_price($stats['paid_commission']) }}</h3>
                                <p class="mb-0 text-muted">{{ __('Paid') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Recent Clicks') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Product') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('IP') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentClicks as $click)
                                                <tr>
                                                    <td>{{ $click->product->name ?? 'N/A' }}</td>
                                                    <td>{{ $click->clicked_at->diffForHumans() }}</td>
                                                    <td>{{ $click->ip_address }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">{{ __('No clicks yet') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Recent Orders') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Order #') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Commission') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders as $order)
                                                <tr>
                                                    <td>#{{ $order->order_id }}</td>
                                                    <td>{{ format_price($order->order_amount) }}</td>
                                                    <td>{{ format_price($order->commission_earned) }}</td>
                                                    <td><span class="badge bg-{{ $order->status == 'paid' ? 'success' : ($order->status == 'approved' ? 'info' : 'warning') }}">{{ ucfirst($order->status) }}</span></td>
                                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">{{ __('No orders yet') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#toggle-reseller-status').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true);
            
            $.ajax({
                url: '{{ route("customer.reseller.toggle-status") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function() {
                    alert('{{ __("Error updating reseller status") }}');
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
