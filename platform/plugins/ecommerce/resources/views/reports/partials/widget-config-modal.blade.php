<x-core::modal
    id="widget-config-modal"
    type="primary"
    :title="trans('plugins/ecommerce::reports.widget_configuration')"
    size="xl"
>
        <p class="text-muted mb-4">
            {{ trans('plugins/ecommerce::reports.widget_configuration_description') }}
        </p>

        <form id="widget-config-form">
            @csrf

            @php
                $categories = [
                    'financial' => [
                        'name' => trans('plugins/ecommerce::reports.financial_metrics'),
                        'icon' => 'ti ti-currency-dollar',
                    ],
                    'activity' => [
                        'name' => trans('plugins/ecommerce::reports.activity_metrics'),
                        'icon' => 'ti ti-activity',
                    ],
                    'additional' => [
                        'name' => trans('plugins/ecommerce::reports.additional_metrics'),
                        'icon' => 'ti ti-plus',
                    ],
                    'analytics' => [
                        'name' => trans('plugins/ecommerce::reports.detailed_analytics'),
                        'icon' => 'ti ti-chart-line',
                    ],
                    'charts' => [
                        'name' => trans('plugins/ecommerce::reports.performance_charts'),
                        'icon' => 'ti ti-chart-bar',
                    ],
                    'distribution' => [
                        'name' => trans('plugins/ecommerce::reports.distribution_charts'),
                        'icon' => 'ti ti-chart-pie',
                    ],
                    'tables' => [
                        'name' => trans('plugins/ecommerce::reports.data_tables'),
                        'icon' => 'ti ti-table',
                    ],
                ];
            @endphp

            <div class="row" id="widget-categories">
                <!-- Categories will be loaded here via AJAX -->
            </div>

            <div class="mt-4 pt-3 border-top">
                <div class="d-flex gap-2 flex-wrap">
                    <x-core::button
                        type="button"
                        color="secondary"
                        size="sm"
                        id="select-all-widgets"
                    >
                        <x-core::icon name="ti ti-checks" />
                        {{ trans('plugins/ecommerce::reports.select_all') }}
                    </x-core::button>

                    <x-core::button
                        type="button"
                        color="secondary"
                        size="sm"
                        id="deselect-all-widgets"
                    >
                        <x-core::icon name="ti ti-square-minus" />
                        {{ trans('plugins/ecommerce::reports.deselect_all') }}
                    </x-core::button>

                    <x-core::button
                        type="button"
                        color="secondary"
                        size="sm"
                        id="reset-to-default"
                    >
                        <x-core::icon name="ti ti-refresh" />
                        {{ trans('plugins/ecommerce::reports.reset_to_default') }}
                    </x-core::button>
                </div>
            </div>
        </form>

    <x-slot:footer>
        <x-core::button
            type="button"
            color="secondary"
            data-bs-dismiss="modal"
        >
            {{ trans('core/base::forms.cancel') }}
        </x-core::button>

        <x-core::button
            type="button"
            color="primary"
            id="save-widget-config"
        >
            <x-core::icon name="ti ti-device-floppy" />
            {{ trans('core/base::forms.save') }}
        </x-core::button>
    </x-slot:footer>
</x-core::modal>

<style>
.widget-config-item {
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
    cursor: pointer;
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

.widget-category-header {
    background: #f8f9fa;
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 1rem;
}

.widget-grid {
    max-height: 400px;
    overflow-y: auto;
}

#widget-config-modal .modal-dialog {
    max-width: 900px;
}

.widget-config-item label {
    cursor: pointer;
}

.icon-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
$(document).ready(function() {
    let widgetConfigData = {};

    // Load widget configuration when modal is opened
    $('#widget-config-modal').on('show.bs.modal', function() {
        loadWidgetConfiguration();
    });

    function loadWidgetConfiguration() {
        $('#widget-categories').html('<div class="text-center p-4"><svg class="icon-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6l0 -3" /><path d="M16.25 7.75l2.15 -2.15" /><path d="M18 12l3 0" /><path d="M16.25 16.25l2.15 2.15" /><path d="M12 18l0 3" /><path d="M7.75 16.25l-2.15 2.15" /><path d="M6 12l-3 0" /><path d="M7.75 7.75l-2.15 -2.15" /></svg> {{ trans("plugins/ecommerce::reports.loading") }}</div>');

        $.ajax({
            url: '{{ route('ecommerce.report.widget-config.get') }}',
            method: 'GET',
            success: function(response) {
                const data = response.data || {};
                widgetConfigData = data.userPreferences || [];
                renderWidgetCategories(data.availableWidgets || {});
            },
            error: function(xhr) {
                console.error('Failed to load widget configuration:', xhr);
                $('#widget-categories').html('<div class="text-center p-4 text-danger">{{ trans("plugins/ecommerce::reports.failed_to_load_configuration") }}</div>');
                Botble.showError('{{ trans("core/base::forms.load_error") }}');
            }
        });
    }

    function renderWidgetCategories(availableWidgets) {
        const categories = @json($categories);

        if (!availableWidgets || Object.keys(availableWidgets).length === 0) {
            $('#widget-categories').html('<div class="text-center p-4 text-warning">{{ trans("plugins/ecommerce::reports.no_widgets_available") }}</div>');
            return;
        }

        let html = '';

        Object.keys(categories).forEach(categoryKey => {
            const category = categories[categoryKey];
            const categoryWidgets = Object.keys(availableWidgets).filter(widgetClass =>
                availableWidgets[widgetClass].category === categoryKey
            );

            if (categoryWidgets.length > 0) {
                html += `
                    <div class="col-12 mb-4">
                        <div class="widget-category-header">
                            <h4 class="mb-2">
                                ${category.name}
                            </h4>
                        </div>
                        <div class="row">
                `;

                categoryWidgets.forEach(widgetClass => {
                    const widget = availableWidgets[widgetClass];
                    const isChecked = Array.isArray(widgetConfigData) ? widgetConfigData.includes(widgetClass) : true;
                    const widgetId = 'widget-' + btoa(widgetClass).replace(/[^a-zA-Z0-9]/g, '');

                    html += `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card widget-config-item">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="form-check me-3">
                                            <input
                                                class="form-check-input widget-checkbox"
                                                type="checkbox"
                                                name="widgets[]"
                                                value="${widgetClass}"
                                                id="${widgetId}"
                                                ${isChecked ? 'checked' : ''}
                                            >
                                        </div>
                                        <div class="flex-fill">
                                            <label
                                                class="form-check-label fw-semibold mb-1 d-block"
                                                for="${widgetId}"
                                            >
                                                ${widget.name}
                                            </label>
                                            <small class="text-muted">
                                                ${widget.description}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            }
        });

        $('#widget-categories').html(html);
    }

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
                $('#widget-config-modal').modal('hide');
                // Reload the page to show updated widgets
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
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

    // Handle clicking on widget cards
    $(document).on('click', '.widget-config-item', function(e) {
        if (e.target.type !== 'checkbox' && e.target.tagName !== 'LABEL') {
            const checkbox = $(this).find('.widget-checkbox');
            checkbox.prop('checked', !checkbox.prop('checked'));
        }
    });
});
</script>
