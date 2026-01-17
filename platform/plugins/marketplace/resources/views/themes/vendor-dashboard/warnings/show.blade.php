@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Warning Detail') }}</h4>
                    <a href="{{ route('marketplace.vendor.warnings.index') }}" class="btn btn-sm btn-secondary">{{ __('Back to List') }}</a>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $warning->title }}</h5>
                        <p class="text-muted">
                            {{ __('Issued on') }}: {{ $warning->created_at->format('M d, Y H:i') }} | 
                            {{ __('Severity') }}: <span class="badge bg-{{ $warning->severity == 'critical' ? 'danger' : ($warning->severity == 'warning' ? 'warning' : 'info') }}">{{ ucfirst($warning->severity) }}</span>
                        </p>
                    </div>
                    <hr>
                    <div class="warning-content p-3 bg-light rounded mb-4">
                        {!! nl2br(e($warning->content)) !!}
                    </div>

                    @if(!$warning->acknowledged)
                        <div class="alert alert-info">
                            {{ __('Please acknowledge that you have read and understood this warning.') }}
                        </div>
                        <form action="{{ route('marketplace.vendor.warnings.acknowledge', $warning->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('I Acknowledge this Warning') }}
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> {{ __('Acknowledged on') }}: {{ $warning->acknowledged_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
