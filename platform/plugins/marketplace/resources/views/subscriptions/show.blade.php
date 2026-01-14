@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('plugins/marketplace::subscription.subscriptions.view', ['id' => $subscription->id]) }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/marketplace::subscription.subscriptions.vendor') }}</h5>
                            <p>
                                @if($subscription->customer)
                                    <a href="{{ route('customers.edit', $subscription->customer->id) }}">
                                        {{ $subscription->customer->name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/marketplace::subscription.subscriptions.store') }}</h5>
                            <p>
                                @if($subscription->store)
                                    <a href="{{ route('marketplace.stores.edit', $subscription->store->id) }}">
                                        {{ $subscription->store->name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/marketplace::subscription.subscriptions.plan') }}</h5>
                            <p>
                                @if($subscription->plan)
                                    <a href="{{ route('marketplace.subscription-plans.edit', $subscription->plan->id) }}">
                                        {{ $subscription->plan->name }}
                                    </a> ({{ format_price($subscription->plan->price) }} / {{ $subscription->plan->duration }})
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('core/base::tables.status') }}</h5>
                            <p>
                                @php
                                    $statusClass = match ($subscription->status) {
                                        'active' => 'success',
                                        'expired' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($subscription->status) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/marketplace::subscription.subscriptions.starts_at') }}</h5>
                            <p>{{ $subscription->starts_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/marketplace::subscription.subscriptions.expires_at') }}</h5>
                            <p>{{ $subscription->expires_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
