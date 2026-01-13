@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @php
        do_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, request(), WIDGET_MANAGER_MODULE_SCREEN_NAME);
    @endphp
    <div
        class="widget-main"
        id="wrap-widgets"
    >
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">{{ trans('packages/widget::widget.name') }}</div>
                    <h2 class="page-title">{{ trans('packages/widget::widget.manage_widgets') }}</h2>
                </div>
            </div>
        </div>

        <x-core::alert type="info" class="mb-4" :icon="false">
            <div class="d-flex">
                <div class="me-2">
                    <x-core::icon name="ti ti-info-circle" />
                </div>
                <div>
                    {{ trans('packages/widget::widget.usage_instruction') }}
                </div>
            </div>
        </x-core::alert>

        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <x-core::card class="h-100">
                    <x-core::card.header>
                        <x-core::card.title class="d-flex align-items-center">
                            <x-core::icon name="ti ti-layout-grid" class="me-2" />
                            {{ trans('packages/widget::widget.available') }}
                        </x-core::card.title>
                    </x-core::card.header>
                    <x-core::card.body>
                        <div
                            id="wrap-widget-1"
                            class="row row-cols-2 g-3"
                        >
                            @foreach (Widget::getWidgets() as $widget)
                                <li
                                    data-id="{{ $widget->getId() }}"
                                    class="col-6 widget-item list-unstyled"
                                >
                                    <form method="post" class="h-100">
                                        <input
                                            name="id"
                                            type="hidden"
                                            value="{{ $widget->getId() }}"
                                        >
                                        <div class="card card-sm border-primary border-opacity-10 h-100">
                                            <div class="card-header bg-body border-0 p-3 h-100">
                                                <div class="d-flex align-items-start justify-content-between h-100 w-100">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="card-title mb-1 text-primary text-truncate" title="{{ $widget->getConfig()['name'] }}">
                                                            {{ $widget->getConfig()['name'] }}
                                                        </h5>
                                                        <p class="text-secondary small mb-0 widget-description-truncate" title="{{ $widget->getConfig()['description'] }}">
                                                            {{ $widget->getConfig()['description'] }}
                                                        </p>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-auto">
                                                        <span class="text-primary">
                                                            <x-core::icon
                                                                size="sm"
                                                                name="ti ti-grip-vertical"
                                                            />
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </li>
                            @endforeach
                        </div>
                    </x-core::card.body>
                </x-core::card>
            </div>
            <div
                class="col-12 col-lg-7"
                id="added-widget"
            >
                <x-core::card>
                    <x-core::card.header>
                        <x-core::card.title class="d-flex align-items-center">
                            <x-core::icon name="ti ti-layout-sidebar-right" class="me-2" />
                            {{ trans('packages/widget::widget.widget_areas') }}
                        </x-core::card.title>
                    </x-core::card.header>
                    <x-core::card.body>
                        {!! apply_filters(WIDGET_TOP_META_BOXES, null, WIDGET_MANAGER_MODULE_SCREEN_NAME) !!}
                        <div class="row g-3">
                            @foreach (WidgetGroup::getGroups() as $group)
                                <div
                                    class="col-12 col-xxl-6 sidebar-item"
                                    data-id="{{ $group->getId() }}"
                                >
                                    <div class="card h-100">
                                        <div class="card-header bg-secondary-subtle">
                                            <div>
                                                <h4 class="card-title mb-1 d-flex align-items-center">
                                                    <x-core::icon name="ti ti-layout-dashboard" size="sm" class="me-1" />
                                                    {{ $group->getName() }}
                                                </h4>
                                                @if($group->getDescription())
                                                    <p class="text-secondary small mb-0">{{ $group->getDescription() }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <ul
                                                id="wrap-widget-{{ $loop->index + 2 }}"
                                                class="list-unstyled mb-0"
                                            >
                                                @include('packages/widget::item', [
                                                    'widgetAreas' => $group->getWidgets(),
                                                ])
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-core::card.body>
                </x-core::card>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        'use strict';
        var BWidget = BWidget || {};
        BWidget.routes = {
            'delete': '{{ route('widgets.destroy', ['ref_lang' => BaseHelper::stringify(request()->input('ref_lang'))]) }}',
            'save_widgets_sidebar': '{{ route('widgets.save_widgets_sidebar', ['ref_lang' => BaseHelper::stringify(request()->input('ref_lang'))]) }}'
        };
    </script>
@endpush
