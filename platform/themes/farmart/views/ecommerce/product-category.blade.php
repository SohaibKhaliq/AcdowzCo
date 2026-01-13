@php
   Theme::set('pageDescription', $category->description);
@endphp

@include(Theme::getThemeNamespace('views.ecommerce.products'))
