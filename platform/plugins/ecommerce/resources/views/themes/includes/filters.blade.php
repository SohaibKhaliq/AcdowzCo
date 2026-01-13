@if (EcommerceHelper::hasAnyProductFilters())
    @php
        $dataForFilter = EcommerceHelper::dataForFilter($category ?? null);
        [$categories, $brands, $tags, $rand, $categoriesRequest, $urlCurrent, $categoryId, $maxFilterPrice] = $dataForFilter;
    @endphp

    <div class="bb-shop-sidebar">
        <form action="{{ URL::current() }}" data-action="{{ route('public.products') }}" method="GET" class="bb-product-form-filter">
            @include(EcommerceHelper::viewPath('includes.filters.filter-hidden-fields'))

            {!! apply_filters('theme_ecommerce_products_filter_before', null, $dataForFilter) !!}

            @if (EcommerceHelper::isEnabledFilterProductsByCategories())
                @include(EcommerceHelper::viewPath('includes.filters.categories'))
            @endif

            @if (EcommerceHelper::isEnabledFilterProductsByBrands())
                @include(EcommerceHelper::viewPath('includes.filters.brands'))
            @endif

            @if (EcommerceHelper::isEnabledFilterProductsByTags())
                @include(EcommerceHelper::viewPath('includes.filters.tags'))
            @endif

            @if (EcommerceHelper::isEnabledFilterProductsByPrice() && (! EcommerceHelper::hideProductPrice() || EcommerceHelper::isCartEnabled()))
                @include(EcommerceHelper::viewPath('includes.filters.price'))
            @endif

            @include(EcommerceHelper::viewPath('includes.filters.discounted-only'))

            @if (EcommerceHelper::isEnabledFilterProductsByAttributes())
                @include(EcommerceHelper::viewPath('includes.filters.attributes', ['view' => $view ?? null]))
            @endif

            {!! apply_filters('theme_ecommerce_products_filter_after', null, $dataForFilter) !!}
        </form>
    </div>
@endif
