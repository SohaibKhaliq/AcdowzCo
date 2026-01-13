@php
    Theme::layout('full-width');
@endphp

@if ($posts->isNotEmpty())
    @include(Theme::getThemeNamespace() . '::views.loop', compact('posts'))
@endif
