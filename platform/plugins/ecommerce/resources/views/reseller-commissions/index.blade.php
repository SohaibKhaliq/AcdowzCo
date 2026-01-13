@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row mb-3">
        <div class="col-md-4">
            <x-core::card>
                <x-core::card.body>
                    <h3 class="mb-0">${{ number_format($stats['total_pending'], 2) }}</h3>
                    <p class="text-muted mb-0">{{ trans('Pending') }}</p>
                </x-core::card.body>
            </x-core::card>
        </div>
        <div class="col-md-4">
            <x-core::card>
                <x-core::card.body>
                    <h3 class="mb-0">${{ number_format($stats['total_approved'], 2) }}</h3>
                    <p class="text-muted mb-0">{{ trans('Approved') }}</p>
                </x-core::card.body>
            </x-core::card>
        </div>
        <div class="col-md-4">
            <x-core::card>
                <x-core::card.body>
                    <h3 class="mb-0">${{ number_format($stats['total_paid'], 2) }}</h3>
                    <p class="text-muted mb-0">{{ trans('Paid') }}</p>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>

    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('Reseller Commissions') }}
            </x-core::card.title>
        </x-core::card.header>

        <x-core::card.body>
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="reseller_id" class="form-select">
                            <option value="">{{ trans('All Resellers') }}</option>
                            @foreach($resellers as $id => $name)
                                <option value="{{ $id }}" {{ request('reseller_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">{{ trans('All Statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <x-core::button type="submit" color="primary" class="w-100">
                            {{ trans('Filter') }}
                        </x-core::button>
                    </div>
                </div>
            </form>

            <x-core::table>
                <x-core::table.header>
                    <x-core::table.header.cell>
                        <input type="checkbox" id="select-all-commissions" />
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Order ID') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Reseller') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Order Amount') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Commission Rate') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Commission Earned') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Status') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Date') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Actions') }}</x-core::table.header.cell>
                </x-core::table.header>
                <x-core::table.body>
                    @forelse($commissions as $commission)
                        <x-core::table.body.row>
                            <x-core::table.body.cell>
                                <input type="checkbox" class="commission-checkbox" value="{{ $commission->id }}" data-status="{{ $commission->status }}" />
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <a href="{{ route('orders.edit', $commission->order_id) }}" target="_blank">
                                    #{{ $commission->order_id }}
                                    <x-core::icon name="ti ti-external-link" size="sm" />
                                </a>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <a href="{{ route('reseller-commissions.reseller-details', $commission->reseller_id) }}">
                                    {{ $commission->reseller->name }}
                                </a>
                                <br/>
                                <small class="text-muted">{{ $commission->reseller->reseller_id }}</small>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                ${{ number_format($commission->order_amount, 2) }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ $commission->commission_rate }}%
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <strong>${{ number_format($commission->commission_earned, 2) }}</strong>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <x-core::badge 
                                    :color="match($commission->status) {
                                        'pending' => 'warning',
                                        'approved' => 'info',
                                        'paid' => 'success',
                                        default => 'secondary'
                                    }"
                                >
                                    {{ ucfirst($commission->status) }}
                                </x-core::badge>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ $commission->created_at->format('Y-m-d') }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <div class="btn-list">
                                    @if($commission->status == 'pending')
                                        <x-core::button
                                            tag="button"
                                            size="sm"
                                            color="success"
                                            :outlined="true"
                                            class="btn-approve-commission"
                                            data-id="{{ $commission->id }}"
                                        >
                                            {{ trans('Approve') }}
                                        </x-core::button>
                                    @elseif($commission->status == 'approved')
                                        <x-core::button
                                            tag="button"
                                            size="sm"
                                            color="primary"
                                            :outlined="true"
                                            class="btn-pay-commission"
                                            data-id="{{ $commission->id }}"
                                        >
                                            {{ trans('Mark Paid') }}
                                        </x-core::button>
                                    @endif
                                </div>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @empty
                        <x-core::table.body.row>
                            <x-core::table.body.cell colspan="9" class="text-center">
                                {{ trans('No commissions found') }}
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endforelse
                </x-core::table.body>
            </x-core::table>

            <div class="mt-3">
                <x-core::button
                    tag="button"
                    color="success"
                    id="bulk-approve-btn"
                    disabled
                >
                    {{ trans('Bulk Approve') }}
                </x-core::button>
                <x-core::button
                    tag="button"
                    color="primary"
                    id="bulk-pay-btn"
                    disabled
                >
                    {{ trans('Bulk Mark Paid') }}
                </x-core::button>
            </div>
        </x-core::card.body>

        @if($commissions->hasPages())
            <x-core::card.footer>
                {{ $commissions->links() }}
            </x-core::card.footer>
        @endif
    </x-core::card>
@endsection

@push('footer')
<script>
    $(document).ready(function() {
        $('#select-all-commissions').on('change', function() {
            $('.commission-checkbox').prop('checked', $(this).is(':checked'));
            toggleBulkButtons();
        });

        $('.commission-checkbox').on('change', toggleBulkButtons);

        function toggleBulkButtons() {
            const pending = $('.commission-checkbox[data-status="pending"]:checked').length;
            const approved = $('.commission-checkbox[data-status="approved"]:checked').length;
            $('#bulk-approve-btn').prop('disabled', pending === 0);
            $('#bulk-pay-btn').prop('disabled', approved === 0);
        }

        $('.btn-approve-commission').on('click', function() {
            const id = $(this).data('id');
            $.post('{{ route("reseller-commissions.approve", ":id") }}'.replace(':id', id), {
                _token: '{{ csrf_token() }}'
            }).done(function() {
                window.location.reload();
            });
        });

        $('.btn-pay-commission').on('click', function() {
            const id = $(this).data('id');
            $.post('{{ route("reseller-commissions.pay", ":id") }}'.replace(':id', id), {
                _token: '{{ csrf_token() }}'
            }).done(function() {
                window.location.reload();
            });
        });

        $('#bulk-approve-btn').on('click', function() {
            const ids = $('.commission-checkbox[data-status="pending"]:checked').map(function() {
                return $(this).val();
            }).get();
            $.post('{{ route("reseller-commissions.bulk-approve") }}', {
                _token: '{{ csrf_token() }}',
                ids: ids
            }).done(function() {
                window.location.reload();
            });
        });

        $('#bulk-pay-btn').on('click', function() {
            const ids = $('.commission-checkbox[data-status="approved"]:checked').map(function() {
                return $(this).val();
            }).get();
            $.post('{{ route("reseller-commissions.bulk-pay") }}', {
                _token: '{{ csrf_token() }}',
                ids: ids
            }).done(function() {
                window.location.reload();
            });
        });
    });
</script>
@endpush
