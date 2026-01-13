<ul class="menu">
    @foreach (DashboardMenu::getAll('vendor') as $item)
        @continue(! $item['name'])
        @php
            $hasChildren = !empty($item['children']) && $item['children']->isNotEmpty();
            $isParentActive = $item['active'] || ($hasChildren && $item['children']->contains('active', true));
        @endphp
        <li @class(['has-children' => $hasChildren])>
            <a
                href="{{ $hasChildren ? '#' : $item['url'] }}"
                @class([
                    'active' => $isParentActive,
                    'submenu-toggle' => $hasChildren
                ])
                @if($hasChildren)
                    data-bs-toggle="collapse"
                    data-bs-target="#menu-{{ Str::slug($item['id']) }}"
                    aria-expanded="{{ $isParentActive ? 'true' : 'false' }}"
                @endif
            >
                <x-core::icon :name="$item['icon']" />
                {{ $item['name'] }}
                @if($hasChildren)
                    <x-core::icon name="ti ti-chevron-down" class="menu-arrow" />
                @endif
            </a>

            @if($hasChildren)
                <ul
                    class="menu-submenu collapse @if($isParentActive) show @endif"
                    id="menu-{{ Str::slug($item['id']) }}"
                >
                    @foreach($item['children'] as $child)
                        @continue(! $child['name'])
                        <li>
                            <a
                                href="{{ $child['url'] }}"
                                @class(['active' => $child['active']])
                            >
                                <x-core::icon :name="$child['icon']" />
                                {{ $child['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
