@php
    use Botble\Ecommerce\Models\ProductSpecificationAttributeTranslation;

    $isDefaultLanguage = ProductSpecificationAttributeTranslation::isEditingDefaultLanguage();
@endphp

<select class="form-select" name="specification_table_id" id="specification_table_id" @if (! $isDefaultLanguage) disabled @endif>
    <option value="">{{ trans('plugins/ecommerce::product-specification.product.specification_table.select_none') }}</option>
    @foreach($tables as $value => $label)
        <option value="{{ $value }}" @selected($model->specification_table_id == $value)>{{ $label }}</option>
    @endforeach
</select>

@if (! $isDefaultLanguage)
    <input type="hidden" name="specification_table_id" value="{{ $model->specification_table_id }}">
@endif
