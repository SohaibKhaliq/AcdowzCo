@if (($attributes = $attributes->where('attribute_set_id', $set->id)) && $attributes->isNotEmpty())
    <div class="bb-product-filter-attribute-item">
        <h4 class="bb-product-filter-title">{{ $set->title }}</h4>

        <div class="bb-product-filter-content">
            <ul class="bb-product-filter-items filter-visual">
                @foreach ($attributes as $attribute)
                    <li class="bb-product-filter-item">
                        <input
                            id="attribute-{{ $attribute->id }}"
                            name="attributes[{{ $set->slug }}][]"
                            type="checkbox"
                            value="{{ $attribute->id }}"
                            @checked(in_array($attribute->id, $selected))
                        >
                        <label for="attribute-{{ $attribute->id }}">{{ $attribute->title }}</label>
                        <span class="bb-product-attribute-swatch-display" style="{{ $attribute->getAttributeStyle() }}"></span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
