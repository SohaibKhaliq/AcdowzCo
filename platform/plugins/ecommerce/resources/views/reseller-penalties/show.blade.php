@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('plugins/ecommerce::reseller.penalties.view', ['id' => $penalty->id]) }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/ecommerce::reseller.penalties.reseller') }}</h5>
                            <p>
                                @if($penalty->reseller)
                                    <a href="{{ route('customers.edit', $penalty->reseller->id) }}">
                                        {{ $penalty->reseller->name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/ecommerce::reseller.penalties.amount') }}</h5>
                            <p class="text-danger fw-bold">{{ format_price($penalty->amount) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/ecommerce::reseller.penalties.order') }}</h5>
                            <p>
                                @if($penalty->order)
                                    <a href="{{ route('orders.edit', $penalty->order->id) }}">
                                        {{ $penalty->order->code }}
                                    </a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/ecommerce::reseller.penalties.product') }}</h5>
                            <p>
                                @if($penalty->product)
                                    <a href="{{ route('products.edit', $penalty->product->id) }}">
                                        {{ $penalty->product->name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>{{ trans('plugins/ecommerce::reseller.penalties.reason') }}</h5>
                            <p>{{ $penalty->reason }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>{{ trans('core/base::tables.status') }}</h5>
                            <p>
                                @php
                                    $statusClass = $penalty->status === 'applied' ? 'danger' : 'success';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($penalty->status) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/ecommerce::reseller.penalties.issued_by') }}</h5>
                            <p>
                                @if($penalty->issuedBy)
                                    {{ $penalty->issuedBy->name }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>{{ trans('core/base::tables.created_at') }}</h5>
                            <p>{{ $penalty->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>

                    @if($penalty->wallet)
                        <div class="alert alert-info">
                            <h5>{{ trans('plugins/ecommerce::reseller.wallet.balance') }}</h5>
                            <p class="mb-0">{{ format_price($penalty->wallet->balance) }}</p>
                            @if($penalty->wallet->is_blocked)
                                <p class="text-danger mb-0">{{ trans('plugins/ecommerce::reseller.wallet.blocked') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
