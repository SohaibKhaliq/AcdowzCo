@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('Product Oversight Dashboard') }}
            </x-core::card.title>
        </x-core::card.header>

        <x-core::card.body>
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="vendor_id" class="form-select">
                            <option value="">{{ trans('All Vendors') }}</option>
                            @foreach ($stores as $id => $name)
                                <option value="{{ $id }}" {{ request('vendor_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">{{ trans('All Statuses') }}</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                            </option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="approved_status" class="form-select">
                            <option value="">{{ trans('All Approvals') }}</option>
                            <option value="pending" {{ request('approved_status') == 'pending' ? 'selected' : '' }}>Pending
                                Approval</option>
                            <option value="approved" {{ request('approved_status') == 'approved' ? 'selected' : '' }}>
                                Approved</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <x-core::button type="submit" color="primary" class="w-100">
                            {{ trans('Filter') }}
                        </x-core::button>
                    </div>
                </div>
            </form>

            <x-core::table id="product-oversight-table">
                <x-core::table.header>
                    <x-core::table.header.cell>
                        <input type="checkbox" id="select-all" />
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Product') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Vendor') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Status') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Approval') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Agreement') }}</x-core::table.header.cell>
                    <x-core::table.header.cell>{{ trans('Actions') }}</x-core::table.header.cell>
                </x-core::table.header>
                <x-core::table.body>
                    @forelse($products as $product)
                        <x-core::table.body.row>
                            <x-core::table.body.cell>
                                <input type="checkbox" class="product-checkbox" value="{{ $product->id }}" />
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <a href="{{ route('products.edit', $product->id) }}" target="_blank">
                                    {{ $product->name }}
                                    <x-core::icon name="ti ti-external-link" size="sm" />
                                </a>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <a href="{{ route('marketplace.store.edit', $product->store_id) }}" target="_blank">
                                    {{ $product->store->name ?? 'N/A' }}
                                </a>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <x-core::badge :color="$product->status == 'published' ? 'success' : 'warning'">
                                    {{ ucfirst($product->status) }}
                                </x-core::badge>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                @if ($product->approved_by)
                                    <x-core::icon name="ti ti-check" class="text-success" />
                                    {{ trans('Approved') }}
                                @else
                                    <x-core::badge color="warning">{{ trans('Pending') }}</x-core::badge>
                                @endif
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                @if ($product->store)
                                    <small>{{ ucfirst($product->store->agreement_type ?? 'N/A') }}</small>
                                    <br />
                                    <small
                                        class="text-muted">{{ $product->store->agreement_value ?? 0 }}{{ $product->store->agreement_type == 'commission' ? '%' : '' }}</small>
                                @else
                                    N/A
                                @endif
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                <div class="btn-list">
                                    @if (!$product->approved_by)
                                        <x-core::button tag="button" size="sm" color="success" :outlined="true"
                                            class="btn-approve-product" data-id="{{ $product->id }}">
                                            {{ trans('Approve') }}
                                        </x-core::button>
                                        <x-core::button tag="button" size="sm" color="warning" :outlined="true"
                                            class="btn-reject-product" data-id="{{ $product->id }}">
                                            {{ trans('Reject') }}
                                        </x-core::button>
                                    @endif
                                    <x-core::button tag="button" size="sm" color="danger" :outlined="true"
                                        data-bb-toggle="delete-action"
                                        data-url="{{ route('products.destroy', $product->id) }}">
                                        {{ trans('Delete') }}
                                    </x-core::button>
                                </div>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @empty
                        <x-core::table.body.row>
                            <x-core::table.body.cell colspan="7" class="text-center">
                                {{ trans('No products found') }}
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endforelse
                </x-core::table.body>
            </x-core::table>

            <div class="mt-3">
                <x-core::button tag="button" color="danger" id="bulk-delete-btn" disabled>
                    {{ trans('Bulk Delete Selected') }}
                </x-core::button>
            </div>
        </x-core::card.body>

        @if ($products->hasPages())
            <x-core::card.footer>
                {{ $products->links() }}
            </x-core::card.footer>
        @endif
    </x-core::card>
@endsection

@push('footer')
    <script>
        $(document).ready(function() {
            console.log('Product Oversight JS initialized');

            // Select all checkbox
            $('#select-all').on('change', function() {
                $('.product-checkbox').prop('checked', $(this).is(':checked'));
                toggleBulkActions();
            });

            $('.product-checkbox').on('change', toggleBulkActions);

            function toggleBulkActions() {
                const checked = $('.product-checkbox:checked').length;
                $('#bulk-delete-btn').prop('disabled', checked === 0);
            }

            // Approve product
            $(document).on('click', '.btn-approve-product', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const button = $(this);

                if (confirm('{{ trans('Are you sure you want to approve this product?') }}')) {
                    button.prop('disabled', true);
                    $.ajax({
                        url: '{{ route('marketplace.product-oversight.approve', ':id') }}'.replace(
                            ':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Botble.showSuccess(response.message ||
                                '{{ trans('Product approved successfully') }}');
                            window.location.reload();
                        },
                        error: function(xhr) {
                            button.prop('disabled', false);
                            Botble.showError(xhr.responseJSON?.message ||
                                '{{ trans('An error occurred') }}');
                        }
                    });
                }
            });

            // Reject product
            $(document).on('click', '.btn-reject-product', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const button = $(this);

                if (confirm('{{ trans('Are you sure you want to reject this product?') }}')) {
                    button.prop('disabled', true);
                    $.ajax({
                        url: '{{ route('marketplace.product-oversight.reject', ':id') }}'.replace(
                            ':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Botble.showSuccess(response.message ||
                                '{{ trans('Product rejected successfully') }}');
                            window.location.reload();
                        },
                        error: function(xhr) {
                            button.prop('disabled', false);
                            Botble.showError(xhr.responseJSON?.message ||
                                '{{ trans('An error occurred') }}');
                        }
                    });
                }
            });

            // Bulk delete
            $('#bulk-delete-btn').on('click', function(e) {
                e.preventDefault();
                const ids = $('.product-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    Botble.showError('{{ trans('Please select at least one product') }}');
                    return;
                }

                if (confirm('{{ trans('Are you sure you want to delete selected products?') }}')) {
                    const button = $(this);
                    button.prop('disabled', true);

                    $.ajax({
                        url: '{{ route('marketplace.product-oversight.bulk-delete') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        },
                        success: function(response) {
                            Botble.showSuccess(response.message ||
                                '{{ trans('Products deleted successfully') }}');
                            window.location.reload();
                        },
                        error: function(xhr) {
                            button.prop('disabled', false);
                            Botble.showError(xhr.responseJSON?.message ||
                                '{{ trans('An error occurred') }}');
                        }
                    });
                }
            });
        });
    </script>
@endpush
