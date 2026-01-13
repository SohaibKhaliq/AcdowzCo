@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>Reseller Information</x-core::card.title>
                </x-core::card.header>
                <x-core::card.body>
                    <div class="text-center mb-3">
                        @if($reseller->avatar)
                            <img src="{{ RvMedia::getImageUrl($reseller->avatar, 'thumb') }}" alt="{{ $reseller->name }}" class="avatar avatar-rounded avatar-lg" />
                        @endif
                    </div>
                    <h5 class="text-center mb-3">{{ $reseller->name }}</h5>
                    <dl class="row">
                        <dt class="col-5">Reseller ID:</dt>
                        <dd class="col-7"><code>{{ $reseller->reseller_id }}</code></dd>
                        <dt class="col-5">Email:</dt>
                        <dd class="col-7"><a href="mailto:{{ $reseller->email }}">{{ $reseller->email }}</a></dd>
                        <dt class="col-5">Status:</dt>
                        <dd class="col-7">
                            <x-core::badge :color="$reseller->is_reseller_active ? 'success' : 'secondary'">
                                {{ $reseller->is_reseller_active ? 'Active' : 'Inactive' }}
                            </x-core::badge>
                        </dd>
                        <dt class="col-5">Commission Rate:</dt>
                        <dd class="col-7">{{ $reseller->reseller_commission_rate }}%</dd>
                        <dt class="col-5">Conversion Rate:</dt>
                        <dd class="col-7">{{ number_format($conversionRate, 2) }}%</dd>
                    </dl>
                </x-core::card.body>
            </x-core::card>
        </div>

        <div class="col-md-8 mb-3">
            <div class="row mb-3">
                <div class="col-md-4">
                    <x-core::card>
                        <x-core::card.body>
                            <h3 class="mb-0">{{ $stats['total_clicks'] }}</h3>
                            <p class="text-muted mb-0">Total Clicks</p>
                        </x-core::card.body>
                    </x-core::card>
                </div>
                <div class="col-md-4">
                    <x-core::card>
                        <x-core::card.body>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                            <p class="text-muted mb-0">Total Orders</p>
                        </x-core::card.body>
                    </x-core::card>
                </div>
                <div class="col-md-4">
                    <x-core::card>
                        <x-core::card.body>
                            <h3 class="mb-0">\${{ number_format($stats['lifetime_earnings'], 2) }}</h3>
                            <p class="text-muted mb-0">Total Earned</p>
                        </x-core::card.body>
                    </x-core::card>
                </div>
            </div>

            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>Recent Orders</x-core::card.title>
                </x-core::card.header>
                <x-core::card.body>
                    <x-core::table>
                        <x-core::table.header>
                            <x-core::table.header.cell>Order #</x-core::table.header.cell>
                            <x-core::table.header.cell>Amount</x-core::table.header.cell>
                            <x-core::table.header.cell>Commission</x-core::table.header.cell>
                            <x-core::table.header.cell>Status</x-core::table.header.cell>
                            <x-core::table.header.cell>Date</x-core::table.header.cell>
                        </x-core::table.header>
                        <x-core::table.body>
                            @forelse($recentOrders as $order)
                                <x-core::table.body.row>
                                    <x-core::table.body.cell>
                                        <a href="{{ route('orders.edit', $order->order_id) }}" target="_blank">
                                            #{{ $order->order_id }}
                                        </a>
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>\${{ number_format($order->order_amount, 2) }}</x-core::table.body.cell>
                                    <x-core::table.body.cell>\${{ number_format($order->commission_earned, 2) }}</x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        <x-core::badge :color="match($order->status) { 'pending' => 'warning', 'approved' => 'info', 'paid' => 'success', default => 'secondary' }">
                                            {{ ucfirst($order->status) }}
                                        </x-core::badge>
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>{{ $order->created_at->format('Y-m-d') }}</x-core::table.body.cell>
                                </x-core::table.body.row>
                            @empty
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="5" class="text-center">No orders found</x-core::table.body.cell>
                                </x-core::table.body.row>
                            @endforelse
                        </x-core::table.body>
                    </x-core::table>
                </x-core::card.body>
            </x-core::card>

            <x-core::card class="mt-3">
                <x-core::card.header>
                    <x-core::card.title>Top Performing Products</x-core::card.title>
                </x-core::card.header>
                <x-core::card.body>
                    <x-core::table>
                        <x-core::table.header>
                            <x-core::table.header.cell>Product</x-core::table.header.cell>
                            <x-core::table.header.cell>Clicks</x-core::table.header.cell>
                        </x-core::table.header>
                        <x-core::table.body>
                            @forelse($topProducts as $item)
                                <x-core::table.body.row>
                                    <x-core::table.body.cell>
                                        {{ $item['product']['name'] ?? 'N/A' }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>{{ $item['clicks'] }}</x-core::table.body.cell>
                                </x-core::table.body.row>
                            @empty
                                <x-core::table.body.row>
                                    <x-core::table.body.cell colspan="2" class="text-center">No data</x-core::table.body.cell>
                                </x-core::table.body.row>
                            @endforelse
                        </x-core::table.body>
                    </x-core::table>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@endsection
