@if (is_plugin_active('blog') && $categories->isNotEmpty())
    <div class="widget-sidebar widget-blog-categories">
        <h2 class="widget-title">{!! BaseHelper::clean($config['name'] ?: __('Categories')) !!}</h2>
        <div class="widget__inner">
            <ul>
                @foreach ($categories as $category)
                    <li class="cat-item">
                        <a
                            href="{{ $category->url }}"
                            title="{{ $category->name }}"
                        >{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
