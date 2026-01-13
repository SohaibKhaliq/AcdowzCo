@php
    Theme::set('pageDescription', $brand->description);
@endphp

@include(Theme::getThemeNamespace('views.ecommerce.products'))
