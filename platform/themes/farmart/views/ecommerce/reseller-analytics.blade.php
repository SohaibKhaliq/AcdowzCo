@php
    Theme::set('pageName', __('Reseller Analytics'));
@endphp

{!! Theme::partial('page-header') !!}

<div class="container">
    <div class="row">
        @include('plugins/ecommerce::themes.customers.sidebar')
        
        <div class="col-lg-9 col-md-8 col-12">
            <div class="dashboard-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Analytics') }}</h5>
                        <form method="GET" class="d-inline-block float-end">
                            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="7" {{ request('period', 30) == 7 ? 'selected' : '' }}>{{ __('Last 7 days') }}</option>
                                <option value="30" {{ request('period', 30) == 30 ? 'selected' : '' }}>{{ __('Last 30 days') }}</option>
                                <option value="90" {{ request('period', 30) == 90 ? 'selected' : '' }}>{{ __('Last 90 days') }}</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <canvas id="clicks-chart"></canvas>
                            </div>
                            <div class="col-md-6 mb-3">
                                <canvas id="earnings-chart"></canvas>
                            </div>
                        </div>

                        <h6 class="mb-3">{{ __('Top Performing Products') }}</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Product') }}</th>
                                        <th>{{ __('Clicks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $item)
                                        <tr>
                                            <td>{{ $item['product']['name'] ?? 'N/A' }}</td>
                                            <td>{{ $item['clicks'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">{{ __('No data available') }}</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    $(document).ready(function() {
        const clicksData = @json($clicksData);
        const ordersData = @json($ordersData);
        
        // Clicks Chart
        const clicksCtx = document.getElementById('clicks-chart').getContext('2d');
        new Chart(clicksCtx, {
            type: 'line',
            data: {
                labels: clicksData.map(item => item.date),
                datasets: [{
                    label: '{{ __("Clicks") }}',
                    data: clicksData.map(item => item.count),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '{{ __("Clicks Over Time") }}'
                    }
                }
            }
        });

        // Earnings Chart
        const earningsCtx = document.getElementById('earnings-chart').getContext('2d');
        new Chart(earningsCtx, {
            type: 'bar',
            data: {
                labels: ordersData.map(item => item.date),
                datasets: [{
                    label: '{{ __("Earnings") }}',
                    data: ordersData.map(item => item.earnings),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '{{ __("Earnings Over Time") }}'
                    }
                }
            }
        });
    });
</script>
@endpush
