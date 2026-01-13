@php
    $displayAttributes = $attributes->where('attribute_set_id', $set->id);
@endphp

@if ($displayAttributes && $displayAttributes->isNotEmpty())
    <div
        class="bb-product-attribute-swatch visual-swatches-wrapper attribute-swatches-wrapper"
        data-type="visual"
        data-slug="{{ $set->slug }}"
    >
        <h4 class="bb-product-attribute-swatch-title">{{ $set->title }}:</h4>
        <ul class="bb-product-attribute-swatch-list visual-swatch color-swatch attribute-swatch">
            @foreach ($displayAttributes as $attribute)
                @php
                    $isDisabled = $variationInfo->where('id', $attribute->id)->isEmpty();
                    $style = $attribute->getAttributeStyle($set, $productVariations);
                @endphp
                <li
                    data-slug="{{ $attribute->slug }}"
                    data-id="{{ $attribute->id }}"
                    @if($isDisabled) data-bs-toggle="tooltip" data-bs-title="Not available" @endif
                    @class([
                        'bb-product-attribute-swatch-item attribute-swatch-item',
                        'disabled' => $isDisabled,
                    ])
                >
                    <label>
                        <input
                            type="radio"
                            name="attribute_{{ $set->slug }}_{{ $key }}"
                            data-slug="{{ $attribute->slug }}"
                            @if (! empty($referenceProduct)) data-reference-product="{{ $referenceProduct->slug }}" @endif
                            value="{{ $attribute->id }}"
                            @checked($selected->where('id', $attribute->id)->isNotEmpty())
                            class="product-filter-item"
                            @if($isDisabled) disabled @endif
                        >
                        <span class="bb-product-attribute-swatch-display" @if($style) style="{{ $style }}" @endif></span>
                        <span class="bb-product-attribute-swatch-item-tooltip">{{ $attribute->title }}</span>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
@endif
