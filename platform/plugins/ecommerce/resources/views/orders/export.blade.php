@extends('packages/data-synchronize::export')

@section('export_extra_filters_after')
    @php
        $statuses = \Botble\Ecommerce\Enums\OrderStatusEnum::labels();
    @endphp

    <div class="row mb-3">
        <div class="col-md-3">
            <x-core::form.text-input
                name="limit"
                type="number"
                :label="trans('plugins/ecommerce::order.export.limit')"
                :placeholder="trans('plugins/ecommerce::order.export.limit_placeholder')"
                min="1"
            />
        </div>
        <div class="col-md-3">
            <x-core::form.select
                name="status"
                :label="trans('plugins/ecommerce::order.status')"
                :options="['' => trans('plugins/ecommerce::order.export.all_status')] + $statuses"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <label for="start_date" class="form-label">{{ trans('plugins/ecommerce::order.export.start_date') }}</label>

            {!! Form::datePicker('start_date', null, ['placeholder' => trans('plugins/ecommerce::order.export.start_date_placeholder')]) !!}
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">{{ trans('plugins/ecommerce::order.export.end_date') }}</label>

            {!! Form::datePicker('end_date', null, ['placeholder' => trans('plugins/ecommerce::order.export.end_date_placeholder')]) !!}
        </div>
    </div>
@stop
