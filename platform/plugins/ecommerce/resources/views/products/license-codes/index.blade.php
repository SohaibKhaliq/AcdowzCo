@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/ecommerce::products.license_codes.title') }} - {{ $product->name }}
                        @if($product->is_variation)
                            <small class="text-muted">({{ trans('plugins/ecommerce::products.license_codes.variation_label') }})</small>
                        @endif
                    </x-core::card.title>
                    <div class="card-actions">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">
                            <x-core::icon name="ti ti-arrow-left" />
                            {{ trans('plugins/ecommerce::products.license_codes.back') }}
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-license-code-modal">
                            <x-core::icon name="ti ti-plus" />
                            {{ trans('plugins/ecommerce::products.license_codes.add') }}
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulk-generate-modal">
                            <x-core::icon name="ti ti-refresh" />
                            {{ trans('plugins/ecommerce::products.license_codes.generate') }}
                        </button>
                    </div>
                </x-core::card.header>

                <x-core::card.body>
                    @if($showOutOfStockWarning)
                        <x-core::alert type="danger" class="mb-3">
                            <strong>{{ trans('plugins/ecommerce::products.license_codes.out_of_stock_title') }}</strong><br>
                            {{ trans('plugins/ecommerce::products.license_codes.out_of_stock_message') }}
                        </x-core::alert>
                    @elseif($showLowStockWarning)
                        <x-core::alert type="warning" class="mb-3">
                            <strong>{{ trans('plugins/ecommerce::products.license_codes.low_stock_title') }}</strong><br>
                            {{ trans('plugins/ecommerce::products.license_codes.low_stock_message', ['count' => $availableCount]) }}
                        </x-core::alert>
                    @endif

                    @if($licenseCodes->count() > 0)
                        <x-core::table>
                            <x-core::table.header>
                                <x-core::table.header.cell>
                                    {{ trans('plugins/ecommerce::products.license_codes.code') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ trans('plugins/ecommerce::products.license_codes.status') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ trans('plugins/ecommerce::products.license_codes.assigned_at') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ trans('core/base::tables.created_at') }}
                                </x-core::table.header.cell>
                                <x-core::table.header.cell>
                                    {{ trans('core/base::tables.operations') }}
                                </x-core::table.header.cell>
                            </x-core::table.header>

                            <x-core::table.body>
                                @foreach($licenseCodes as $licenseCode)
                                    <x-core::table.body.row>
                                        <x-core::table.body.cell>
                                            <code>{{ $licenseCode->license_code }}</code>
                                        </x-core::table.body.cell>
                                        <x-core::table.body.cell>
                                            {!! $licenseCode->status->toHtml() !!}
                                        </x-core::table.body.cell>
                                        <x-core::table.body.cell>
                                            @if($licenseCode->assigned_at && $licenseCode->assignedOrderProduct && $licenseCode->assignedOrderProduct->order)
                                                <div>
                                                    {{ BaseHelper::formatDate($licenseCode->assigned_at) }}
                                                    <br>
                                                    <a href="{{ route('orders.edit', $licenseCode->assignedOrderProduct->order->id) }}"
                                                       class="text-primary"
                                                       target="_blank">
                                                        <x-core::icon name="ti ti-external-link" />
                                                        {{ trans('plugins/ecommerce::order.view_order') }} {{ $licenseCode->assignedOrderProduct->order->code }}
                                                    </a>
                                                </div>
                                            @else
                                                {{ $licenseCode->assigned_at ? BaseHelper::formatDate($licenseCode->assigned_at) : '-' }}
                                            @endif
                                        </x-core::table.body.cell>
                                        <x-core::table.body.cell>
                                            {{ BaseHelper::formatDate($licenseCode->created_at) }}
                                        </x-core::table.body.cell>
                                        <x-core::table.body.cell>
                                            @if($licenseCode->isAvailable())
                                                <button type="button"
                                                        class="btn btn-sm btn-warning edit-license-code-btn"
                                                        data-license-code-id="{{ $licenseCode->id }}"
                                                        data-license-code="{{ $licenseCode->license_code }}">
                                                    <x-core::icon name="ti ti-edit" />
                                                    {{ trans('core/base::tables.edit') }}
                                                </button>
                                                <button type="button"
                                                        class="btn btn-sm btn-danger delete-license-code-btn"
                                                        data-license-code-id="{{ $licenseCode->id }}">
                                                    <x-core::icon name="ti ti-trash" />
                                                    {{ trans('core/base::tables.delete') }}
                                                </button>
                                            @else
                                                <span class="text-muted">{{ trans('plugins/ecommerce::products.license_codes.used_code_no_actions') }}</span>
                                            @endif
                                        </x-core::table.body.cell>
                                    </x-core::table.body.row>
                                @endforeach
                            </x-core::table.body>
                        </x-core::table>

                        <div class="mt-3">
                            {!! $licenseCodes->links() !!}
                        </div>
                    @else
                        @if($product->license_code_type === 'pick_from_list')
                            <x-core::alert type="warning">
                                <strong>{{ trans('plugins/ecommerce::products.license_codes.no_codes_warning_title') }}</strong><br>
                                {{ trans('plugins/ecommerce::products.license_codes.no_codes_warning_message') }}
                            </x-core::alert>
                        @else
                            <x-core::alert type="info">
                                {{ trans('plugins/ecommerce::products.license_codes.no_codes_auto_generate') }}
                            </x-core::alert>
                        @endif
                    @endif
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>

    @include('plugins/ecommerce::products.license-codes.modals')
@endsection

@push('footer')
    <script>
        $(document).ready(function() {
            // Add license code
            $('#add-license-code-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('products.license-codes.store', $product->id) }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.error) {
                            Botble.showError(response.message);
                        } else {
                            Botble.showSuccess(response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        Botble.handleError(xhr);
                    }
                });
            });

            // Edit license code
            $(document).on('click', '.edit-license-code-btn', function() {
                const id = $(this).data('license-code-id');
                const code = $(this).data('license-code');

                $('#edit-license-code-id').val(id);
                $('#edit-license-code').val(code);
                $('#edit-license-code-modal').modal('show');
            });

            $('#edit-license-code-form').on('submit', function(e) {
                e.preventDefault();

                const id = $('#edit-license-code-id').val();

                $.ajax({
                    url: '{{ route('products.license-codes.update', [$product->id, '__ID__']) }}'.replace('__ID__', id),
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.error) {
                            Botble.showError(response.message);
                        } else {
                            Botble.showSuccess(response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        Botble.handleError(xhr);
                    }
                });
            });

            // Delete license code
            $(document).on('click', '.delete-license-code-btn', function() {
                const id = $(this).data('license-code-id');

                if (confirm('{{ trans('core/base::tables.confirm_delete') }}')) {
                    $.ajax({
                        url: '{{ route('products.license-codes.destroy', [$product->id, '__ID__']) }}'.replace('__ID__', id),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.error) {
                                Botble.showError(response.message);
                            } else {
                                Botble.showSuccess(response.message);
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            Botble.handleError(xhr);
                        }
                    });
                }
            });

            // Bulk generate
            $('#bulk-generate-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('products.license-codes.bulk-generate', $product->id) }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.error) {
                            Botble.showError(response.message);
                        } else {
                            Botble.showSuccess(response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        Botble.handleError(xhr);
                    }
                });
            });

            // Handle format change
            $('#license-code-format').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#custom-pattern-group').show();
                } else {
                    $('#custom-pattern-group').hide();
                }
            });
        });
    </script>
@endpush
