@foreach($products as $product)
    @include(Theme::getThemeNamespace('views.ecommerce.includes.product-item'))
@endforeach
