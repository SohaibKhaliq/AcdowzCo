@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Official Warning Letters') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Severity') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warnings as $warning)
                            <tr>
                                <td>{{ $warning->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $warning->title }}</td>
                                <td>
                                    @php
                                        $color = match($warning->severity) {
                                            'critical' => 'danger',
                                            'warning' => 'warning',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($warning->severity) }}</span>
                                </td>
                                <td>
                                    @if($warning->acknowledged)
                                        <span class="text-success"><i class="fa fa-check"></i> {{ __('Acknowledged') }}</span>
                                    @else
                                        <span class="text-warning"><i class="fa fa-clock"></i> {{ __('Pending Action') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('marketplace.vendor.warnings.show', $warning->id) }}" class="btn btn-sm btn-primary">
                                        {{ __('View Details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No warnings found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {!! $warnings->links() !!}
            </div>
        </div>
    </div>
@endsection
