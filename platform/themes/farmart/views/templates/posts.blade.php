@if ($posts->isNotEmpty())
    @include(Theme::getThemeNamespace() . '::views.loop', compact('posts'))
@endif
