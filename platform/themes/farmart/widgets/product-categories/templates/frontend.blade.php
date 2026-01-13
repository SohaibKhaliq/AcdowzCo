@if (is_plugin_active('ecommerce') && ! empty($categories))
    <div>
        <p>
            @if ($config['name'])
                <strong>{{ $config['name'] }}:</strong>
            @endif

            @foreach ($categories as $category)
                <a
                    href="{{ route('public.single', $category->url) }}"
                    title="{{ $category->name }}"
                >{{ $category->name }}</a>
            @endforeach
        </p>
    </div>
@endif
