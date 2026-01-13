@php
    Theme::layout('full-width');
    Theme::asset()
        ->container('footer')
        ->remove('ecommerce-utilities-js');
@endphp
{!! Theme::partial('page-header', ['size' => 'xxxl']) !!}

<div class="container-xxxl">
    <div class="row my-4">
        <div class="col-md-3">
            <ul class="nav flex-column dashboard-navigation mb-5">
                @foreach (DashboardMenu::getAll('customer') as $item)
                    <li class="nav-item" id="{{ $item['id'] }}">
                        <a
                            class="nav-link
                            @if ($item['active']) active @endif"
                            href="{{ $item['url']  }}"
                            aria-current="@if ($item['active']) true @else false @endif"
                        >
                            @if ($item['icon'])
                                <x-core::icon :name="$item['icon']" />
                            @endif
                            {{ __($item['name']) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-9 customer-page">
            <div class="customer-dashboard-container">
                <div class="customer-dashboard-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
