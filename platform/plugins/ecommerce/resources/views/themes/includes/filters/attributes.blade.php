@php
    $productSwatchesRendered = render_product_swatches_filter(isset($view) ? compact('categoryId', 'view') : compact('categoryId'));
@endphp

@if ($productSwatchesRendered)
    <div class="bb-product-filter bb-product-filter-attributes">
        {!! $productSwatchesRendered !!}
    </div>
@endif
