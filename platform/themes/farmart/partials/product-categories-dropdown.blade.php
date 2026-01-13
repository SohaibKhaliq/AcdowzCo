@php
    $groupedCategories = ProductCategoryHelper::getProductCategoriesWithUrl()->groupBy('parent_id');

    $currentCategories = $groupedCategories->get(0);
@endphp

@if($currentCategories)
    @foreach ($currentCategories as $category)
        @php
            $hasChildren = $groupedCategories->has($category->id);
            $iconImage = $category->icon_image;
            $icon = $category->icon;
        @endphp

        <li @if ($hasChildren) class="menu-item-has-children has-mega-menu" @endif>
            <a href="{{ route('public.single', $category->url) }}">
                @if ($iconImage)
                    {{ RvMedia::image($iconImage, __('Icon'), attributes: ['loading' => false, 'style' => 'width: 18px; height: 18px']) }}
                @elseif ($icon)
                    {!! BaseHelper::renderIcon($icon) !!}
                @endif
                <span class="ms-1">{{ $category->name }}</span>
                @if ($hasChildren)
                    <span class="sub-toggle">
                    <span class="svg-icon">
                        <svg>
                            <use
                                href="#svg-icon-chevron-right"
                                xlink:href="#svg-icon-chevron-right"
                            ></use>
                        </svg>
                    </span>
                </span>
                @endif
            </a>
            @if ($hasChildren)
                @php
                    $currentCategories = $groupedCategories->get($category->id);
                @endphp

                <div class="mega-menu" @if(! $groupedCategories->has($currentCategories[0]->id)) style="min-width: 250px;" @endif>
                    <div class="mega-menu-wrapper">
                        @if($currentCategories)
                            @foreach ($currentCategories as $childCategory)
                                @php
                                    $hasChildren = $groupedCategories->has($childCategory->id);
                                @endphp
                                <div class="mega-menu__column">
                                    @if ($hasChildren)
                                        <a href="{{ route('public.single', $childCategory->url) }}">
                                            <h4>
                                                @if ($childCategory->icon_image)
                                                    <img
                                                        src="{{ RvMedia::getImageUrl($childCategory->icon_image) }}"
                                                        alt="{{ $childCategory->name }}"
                                                        width="18"
                                                        height="18"
                                                        style="vertical-align: top;"
                                                    >
                                                @elseif ($childCategory->icon)
                                                    <i class="{{ $childCategory->icon }}"></i>
                                                @endif
                                                <span class="ms-1">{{ $childCategory->name }}</span>
                                            </h4>
                                            <span class="sub-toggle">
                                        <span class="svg-icon">
                                            <svg>
                                                <use
                                                    href="#svg-icon-chevron-right"
                                                    xlink:href="#svg-icon-chevron-right"
                                                ></use>
                                            </svg>
                                        </span>
                                    </span>
                                        </a>
                                        <ul class="mega-menu__list">
                                            @php
                                                $currentCategories = $groupedCategories->get($childCategory->id);
                                            @endphp
                                            @if($currentCategories)
                                                @foreach ($currentCategories as $item)
                                                    <li>
                                                        <a href="{{ route('public.single', $item->url) }}">
                                                            @if ($item->icon_image)
                                                                <img
                                                                    src="{{ RvMedia::getImageUrl($item->icon_image) }}"
                                                                    alt="{{ $item->name }}"
                                                                    width="18"
                                                                    height="18"
                                                                    style="vertical-align: top;"
                                                                >
                                                            @elseif ($item->icon)
                                                                <i class="{{ $item->icon }}"></i>
                                                            @endif
                                                            <span class="ms-1">{{ $item->name }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    @else
                                        <a href="{{ route('public.single', $childCategory->url) }}">{{ $childCategory->name }}</a>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
        </li>
    @endforeach
@endif
