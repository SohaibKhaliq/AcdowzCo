@if (count($widgetAreas))
    @foreach ($widgetAreas as $item)
        @continue(! class_exists($item->widget_id, false))

        @php
            $widget = new $item->widget_id();
        @endphp

        <li
            data-id="{{ $widget->getId() }}"
            data-position="{{ $item->position }}"
            class="mb-3 widget-item list-unstyled"
        >
            <div class="card border">
                <div class="card-header bg-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center flex-grow-1">
                            <span class="me-2 text-secondary">
                                <x-core::icon
                                    size="sm"
                                    name="ti ti-grip-vertical"
                                />
                            </span>
                            <h5 class="mb-0 fw-semibold">{{ $widget->getConfig()['name'] }}</h5>
                        </div>
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-ghost-secondary p-1" type="button">
                                <x-core::icon
                                    size="sm"
                                    name="ti ti-chevron-down"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-content border-top-0" style="display: none;">
                    <div class="card-body">
                        <form method="post">
                            <input
                                name="id"
                                type="hidden"
                                value="{{ $widget->getId() }}"
                            >
                            {!! $widget->form($item->sidebar_id, $item->position) !!}
                            <div class="widget-control-actions mt-3 d-flex justify-content-between gap-2">
                                <x-core::button
                                    type="button"
                                    :outlined="true"
                                    color="danger"
                                    size="sm"
                                    class="widget-control-delete"
                                >
                                    <x-core::icon name="ti ti-trash" size="sm" class="me-1" />
                                    {{ trans('packages/widget::widget.delete') }}
                                </x-core::button>

                                <x-core::button
                                    type="button"
                                    color="primary"
                                    size="sm"
                                    class="widget-save"
                                >
                                    <x-core::icon name="ti ti-device-floppy" size="sm" class="me-1" />
                                    {{ trans('core/base::forms.save_and_continue') }}
                                </x-core::button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </li>
    @endforeach
@else
    <li class="dropzone list-unstyled">
        <div class="card border-2 border-dashed bg-secondary-subtle">
            <div class="card-body text-center py-4">
                <div class="mb-2">
                    <x-core::icon name="ti ti-drag-drop-2" size="lg" class="text-secondary" />
                </div>
                <p class="text-secondary mb-0">{{ trans('packages/widget::widget.drag_widget_to_sidebar') }}</p>
            </div>
        </div>
    </li>
@endif
