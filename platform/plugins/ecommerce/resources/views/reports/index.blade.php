@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header-action')
    <div class="d-flex gap-2">
        <x-core::button
            type="button"
            color="secondary"
            :outlined="true"
            data-bs-toggle="modal"
            data-bs-target="#widget-config-modal"
            icon="ti ti-settings"
        >
            {{ trans('plugins/ecommerce::reports.configure_widgets') }}
        </x-core::button>

        <x-core::button
            type="button"
            color="primary"
            :outlined="true"
            class="date-range-picker"
            data-format-value="{{ trans('plugins/ecommerce::reports.date_range_format_value', ['from' => '__from__', 'to' => '__to__']) }}"
            data-format="{{ Str::upper(config('core.base.general.date_format.js.date')) }}"
            data-href="{{ route('ecommerce.report.index') }}"
            data-start-date="{{ $startDate }}"
            data-end-date="{{ $endDate }}"
            icon="ti ti-calendar"
        >
            {{ trans('plugins/ecommerce::reports.date_range_format_value', [
                'from' => BaseHelper::formatDate($startDate),
                'to' => BaseHelper::formatDate($endDate),
            ]) }}
        </x-core::button>
    </div>
@endpush

@section('content')
    <div id="report-stats-content">
        @include('plugins/ecommerce::reports.ajax')
    </div>
    @include('plugins/ecommerce::reports.partials.widget-config-modal')
@endsection

@push('footer')
    <script>
        var BotbleVariables = BotbleVariables || {};
        BotbleVariables.languages = BotbleVariables.languages || {};
        BotbleVariables.languages.reports = {!! json_encode(trans('plugins/ecommerce::reports.ranges'), JSON_HEX_APOS) !!}
    </script>
@endpush
