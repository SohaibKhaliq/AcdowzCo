@if (is_plugin_active('blog'))
    @php
        $tags = get_popular_tags($config['number_display']);
    @endphp
    @if ($tags->isNotEmpty())
        <div class="widget-sidebar widget-blog-tag-cloud">
            <h2 class="widget-title">{{ BaseHelper::clean($config['name'] ?: __('Tags')) }}</h2>
            <div class="widget__inner">
                @foreach ($tags as $tag)
                    <a
                        class="tag-cloud-link"
                        href="{{ $tag->url }}"
                        title="{{ $tag->name }}"
                        aria-label="{{ $tag->name }}"
                    >{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
    @endif
@endif
