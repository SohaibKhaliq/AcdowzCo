@php
    $attributes['class'] = Arr::get($attributes, 'class', '') . str_replace(Arr::get($attributes, 'class'), '', ' form-control');
    $attributes['placeholder'] = 'HH:MM';
    $attributes['data-input'] = '';
    $attributes['readonly'] = $attributes['readonly'] ?? 'readonly';

    if (! empty($attributes['data-options'])) {
        $attributes['data-options'] = is_string($attributes['data-options']) ? $attributes['data-options'] : json_encode($attributes['data-options']);
    }
@endphp

<div class="input-group timepicker">
    {!! Form::text($name, $value, $attributes) !!}
    <x-core::button data-toggle icon="ti ti-clock" :icon-only="true" />
    <x-core::button data-clear icon="ti ti-x" :icon-only="true" class="text-danger" />
</div>
