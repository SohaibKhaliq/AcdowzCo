@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-12">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/ecommerce::reports.widget_configuration') }}
                    </x-core::card.title>
                    <div class="card-actions">
                        <x-core::button
                            type="button"
                            color="primary"
                            id="save-widget-config"
                        >
                            <x-core::icon name="ti ti-device-floppy" />
                            {{ trans('core/base::forms.save') }}
                        </x-core::button>
                    </div>
                </x-core::card.header>
                <x-core::card.body>
                    <p class="text-muted mb-4">
                        {{ trans('plugins/ecommerce::reports.widget_configuration_description') }}
                    </p>

                    <form id="widget-config-form">
                        @csrf

                        @php
                            $categories = [
                                'financial' => trans('plugins/ecommerce::reports.financial_metrics'),
                                'activity' => trans('plugins/ecommerce::reports.activity_metrics'),
                                'additional' => trans('plugins/ecommerce::reports.additional_metrics'),
                                'analytics' => trans('plugins/ecommerce::reports.detailed_analytics'),
                                'charts' => trans('plugins/ecommerce::reports.performance_charts'),
                                'distribution' => trans('plugins/ecommerce::reports.distribution_charts'),
                                'tables' => trans('plugins/ecommerce::reports.data_tables'),
                            ];
                        @endphp

                        @foreach($categories as $categoryKey => $categoryName)
                            @php
                                $categoryWidgets = collect($availableWidgets)->filter(function($widget) use ($categoryKey) {
                                    return $widget['category'] === $categoryKey;
                                });
                            @endphp

                            @if($categoryWidgets->isNotEmpty())
                                <div class="mb-4">
                                    <h5 class="mb-3">
                                        <x-core::icon name="ti ti-folder" class="me-2" />
                                        {{ $categoryName }}
                                    </h5>

                                    <div class="row">
                                        @foreach($categoryWidgets as $widgetClass => $widget)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card widget-config-item">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-start">
                                                            <div class="form-check me-3">
                                                                <input
                                                                    class="form-check-input widget-checkbox"
                                                                    type="checkbox"
                                                                    name="widgets[]"
                                                                    value="{{ $widgetClass }}"
                                                                    id="widget-{{ md5($widgetClass) }}"
                                                                    @checked(in_array($widgetClass, $userPreferences))
                                                                >
                                                            </div>
                                                            <div class="flex-fill">
                                                                <label
                                                                    class="form-check-label fw-semibold mb-1 d-block"
                                                                    for="widget-{{ md5($widgetClass) }}"
                                                                >
                                                                    {{ $widget['name'] }}
                                                                </label>
                                                                <small class="text-muted">
                                                                    {{ $widget['description'] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex gap-2">
                                <x-core::button
                                    type="button"
                                    color="secondary"
                                    id="select-all-widgets"
                                >
                                    <x-core::icon name="ti ti-checks" />
                                    {{ trans('plugins/ecommerce::reports.select_all') }}
                                </x-core::button>

                                <x-core::button
                                    type="button"
                                    color="secondary"
                                    id="deselect-all-widgets"
                                >
                                    <x-core::icon name="ti ti-square-minus" />
                                    {{ trans('plugins/ecommerce::reports.deselect_all') }}
                                </x-core::button>

                                <x-core::button
                                    type="button"
                                    color="secondary"
                                    id="reset-to-default"
                                >
                                    <x-core::icon name="ti ti-refresh" />
                                    {{ trans('plugins/ecommerce::reports.reset_to_default') }}
                                </x-core::button>
                            </div>
                        </div>
                    </form>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        $(document).ready(function() {
            // Save configuration
            $('#save-widget-config').on('click', function() {
                const $button = $(this);
                const originalText = $button.html();

                $button.prop('disabled', true).html('<svg class="icon-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6l0 -3" /><path d="M16.25 7.75l2.15 -2.15" /><path d="M18 12l3 0" /><path d="M16.25 16.25l2.15 2.15" /><path d="M12 18l0 3" /><path d="M7.75 16.25l-2.15 2.15" /><path d="M6 12l-3 0" /><path d="M7.75 7.75l-2.15 -2.15" /></svg> {{ trans("plugins/ecommerce::ecommerce.saving") }}');

                const formData = new FormData($('#widget-config-form')[0]);

                $.ajax({
                    url: '{{ route('ecommerce.report.widget-config.save') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Botble.showSuccess(response.message || '{{ trans("plugins/ecommerce::reports.widget_configuration_saved") }}');
                    },
                    error: function(xhr) {
                        Botble.showError(xhr.responseJSON?.message || '{{ trans("core/base::forms.save_error") }}');
                    },
                    complete: function() {
                        $button.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Select all widgets
            $('#select-all-widgets').on('click', function() {
                $('.widget-checkbox').prop('checked', true);
            });

            // Deselect all widgets
            $('#deselect-all-widgets').on('click', function() {
                $('.widget-checkbox').prop('checked', false);
            });

            // Reset to default (all selected)
            $('#reset-to-default').on('click', function() {
                $('.widget-checkbox').prop('checked', true);
            });
        });
    </script>
@endpush

@push('header')
    <style>
        .widget-config-item {
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .widget-config-item:hover {
            border-color: #007bff;
            box-shadow: 0 2px 4px rgba(0,123,255,0.1);
        }

        .widget-config-item .form-check-input:checked ~ .flex-fill {
            opacity: 1;
        }

        .widget-config-item .form-check-input:not(:checked) ~ .flex-fill {
            opacity: 0.6;
        }

        .icon-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
@endpush
