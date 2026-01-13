@if ($sharingButtons = \Botble\Theme\Supports\ThemeSupport::getSocialSharingButtons($product->url, $product->description, $product->image))
    <ul class="widget-socials-share widget-socials__text">
        @foreach($sharingButtons as $button)
            <li>
                @php
                    $button['background_color'] = $button['background_color'] ?: (theme_option('primary_button_background_color') ?: theme_option('primary_color', '#fab528'));
                    $button['color'] = $button['color'] ?? '#fff';
                @endphp
                <a
                    href="{{ $button['url'] }}"
                    title="{{ $button['name'] }}"
                    target="_blank"
                    @style(["background-color: {$button['background_color']}" => $button['background_color'], "color: {$button['color']}" => $button['color']])
                >
                    {!! $button['icon'] !!}

                    <span class="text">{{ $button['name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
@endif

