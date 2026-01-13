@if ($brands->isNotEmpty())
    <div class="bb-product-filter">
        <h4 class="bb-product-filter-title">{{ __('Brands') }}</h4>

        <div class="bb-product-filter-content">
            <ul class="bb-product-filter-items filter-checkbox">
                @foreach ($brands as $brand)
                    <li class="bb-product-filter-item">
                        @php
                            $requestBrands = EcommerceHelper::parseFilterParams(request(), 'brands');
                        @endphp
                        <input id="attribute-brand-{{ $brand->id }}" type="checkbox" name="brands[]" value="{{ $brand->id }}" @checked(in_array($brand->id, $requestBrands)) />
                        <label for="attribute-brand-{{ $brand->id }}">{{ $brand->name }}</label>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

