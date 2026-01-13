@php
    Theme::set('pageDescription', $tag->description);
@endphp

@include(Theme::getThemeNamespace('views.ecommerce.products'))
