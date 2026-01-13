@php
    use Botble\Ecommerce\Models\ProductSpecificationAttributeTranslation;
    use Botble\Ecommerce\Models\SpecificationTable;

    $currentLangCode = ProductSpecificationAttributeTranslation::getCurrentLanguageCode($language ?? null);
    $isDefaultLanguage = ProductSpecificationAttributeTranslation::isEditingDefaultLanguage();

    $groupedAttributes = $specificationTable->getSortedAttributesForProduct($product);
@endphp

<div class="table-responsive">
    <x-core::table class="table-bordered">
        <x-core::table.header>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::product-specification.product.specification_table.group') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::product-specification.product.specification_table.attribute') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::product-specification.product.specification_table.value') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell class="text-center">
                {{ trans('plugins/ecommerce::product-specification.product.specification_table.hide') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell class="text-center" style="width: 40px;">
                {{ trans('plugins/ecommerce::product-specification.product.specification_table.sorting') }}
            </x-core::table.header.cell>
        </x-core::table.header>
        <x-core::table.body>
            @foreach ($groupedAttributes as $groupData)
                @foreach ($groupData['attributes'] as $attribute)
                    @php
                        $data = SpecificationTable::getAttributeDisplayData($product, $attribute, $currentLangCode);
                        $attributeValue = $data['displayValue'];
                    @endphp

                    <x-core::table.body.row>
                        <x-core::table.body.cell>{{ $groupData['group']->name }}</x-core::table.body.cell>
                        <x-core::table.body.cell>{{ $attribute->name }}</x-core::table.body.cell>
                        <x-core::table.body.cell>
                            @if ($isDefaultLanguage)
                                @if ($attribute->type == 'text')
                                    <input class="form-control" type="text" name="specification_attributes[{{ $attribute->id }}][value]" value="{{ $attributeValue }}" placeholder="{{ trans('plugins/ecommerce::product-specification.product.specification_table.enter_value') }}">
                                @elseif ($attribute->type == 'textarea')
                                    <textarea class="form-control" name="specification_attributes[{{ $attribute->id }}][value]" placeholder="{{ trans('plugins/ecommerce::product-specification.product.specification_table.enter_value') }}">{{ $attributeValue }}</textarea>
                                @elseif ($attribute->type == 'checkbox')
                                    <input class="form-check-input" type="checkbox" name="specification_attributes[{{ $attribute->id }}][value]" value="1" @checked($attributeValue)>
                                @elseif ($attribute->type == 'select')
                                    <select class="form-select" name="specification_attributes[{{ $attribute->id }}][value]">
                                        @foreach ($attribute->options as $value)
                                            <option value="{{ $value }}" @selected($value === $attributeValue)>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @elseif ($attribute->type == 'radio')
                                    @foreach ($attribute->options as $value)
                                        <label class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="specification_attributes[{{ $attribute->id }}][value]" value="{{ $value }}" @checked($value === $attributeValue)>
                                            <span class="form-check-label">{{ $value }}</span>
                                        </label>
                                    @endforeach
                                @endif
                            @else
                                @if ($attribute->type == 'text')
                                    <input class="form-control" type="text" name="specification_attributes[{{ $attribute->id }}][value]" value="{{ $attributeValue }}" placeholder="{{ trans('plugins/ecommerce::product-specification.product.specification_table.enter_translation') }}">
                                @elseif ($attribute->type == 'textarea')
                                    <textarea class="form-control" name="specification_attributes[{{ $attribute->id }}][value]" placeholder="{{ trans('plugins/ecommerce::product-specification.product.specification_table.enter_translation') }}">{{ $attributeValue }}</textarea>
                                @else
                                    <span class="text-muted">{{ $data['defaultValue'] ?: trans('plugins/ecommerce::product-specification.product.specification_table.not_set') }}</span>
                                @endif
                            @endif
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            @if ($isDefaultLanguage)
                                <input class="form-check-input" type="checkbox" name="specification_attributes[{{ $attribute->id }}][hidden]" value="1" @checked($data['isHidden'])>
                            @else
                                @if($data['isHidden'])
                                    <x-core::icon name="ti ti-check" class="text-muted" />
                                @else
                                    <x-core::icon name="ti ti-minus" class="text-muted" />
                                @endif
                            @endif
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-center">
                            @if ($isDefaultLanguage)
                                <input type="hidden" name="specification_attributes[{{ $attribute->id }}][order]" value="{{ $data['order'] }}">
                                <x-core::icon name="ti ti-arrows-sort" class="text-secondary" style="cursor: move;" />
                            @else
                                <span class="text-muted">{{ $data['order'] }}</span>
                            @endif
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                @endforeach
            @endforeach
        </x-core::table.body>
    </x-core::table>
</div>
