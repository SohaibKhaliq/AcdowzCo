<div class="bb-customer-page crop-avatar">
    <div class="container">
        <div class="customer-body">
            <!-- Mobile Header -->
            <div class="d-lg-none bg-white border-bottom p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        @if ($customer = auth('customer')->user())
                            <div class="wrapper-image" style="width: 32px; height: 32px;">
                                {!! RvMedia::image($customer->avatar_url, $customer->name, attributes: ['class' => 'rounded-circle img-fluid']) !!}
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $customer->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ __('Account Dashboard') }}</div>
                            </div>
                        @endif
                    </div>
                    <button
                        class="btn btn-outline-secondary btn-sm"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#customerSidebar"
                        aria-controls="customerSidebar"
                    >
                        <x-core::icon name="ti ti-menu-2" size="sm" />
                    </button>
                </div>
            </div>

            <div class="row g-0">
                <!-- Desktop Sidebar -->
                <div class="col-lg-3 col-xl-3 d-none d-lg-block">
                    <div class="bb-customer-sidebar-wrapper h-100 d-flex flex-column">
                        <div class="bb-customer-sidebar flex-1">
                            <!-- User Profile Section -->
                            @if ($customer = auth('customer')->user())
                                <div class="bb-customer-sidebar-heading">
                                    <div class="d-flex align-items-center gap-3 p-4">
                                        <div class="position-relative">
                                            <div class="wrapper-image">
                                                {!! RvMedia::image($customer->avatar_url, $customer->name, attributes: ['class' => 'rounded-circle border border-2 border-white shadow-sm']) !!}
                                            </div>
                                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" style="width: 12px; height: 12px;"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="name fw-semibold text-truncate">{{ $customer->name }}</div>
                                            <div class="email text-muted small text-truncate">{{ $customer->email }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Navigation Section -->
                            <nav class="bb-customer-navigation px-3 pb-4">
                                <div class="nav-section">
                                    <ul class="nav nav-pills flex-column gap-1 mt-3">
                                        @foreach (DashboardMenu::getAll('customer') as $item)
                                            @continue(! $item['name'])
                                            <li class="nav-item">
                                                <a
                                                    href="{{ $item['url'] }}"
                                                    @class([
                                                        'nav-link d-flex align-items-center gap-3 rounded-2 py-2 px-3',
                                                        'active' => $item['active']
                                                    ])
                                                    title="{{ $item['name'] }}"
                                                >
                                                    <x-core::icon :name="$item['icon']" class="nav-icon flex-shrink-0" size="sm" />
                                                    <span class="nav-text">{{ $item['name'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-xl-9">
                    <div class="bb-profile-content">
                        <!-- Page Header -->
                        <div class="bb-profile-header">
                            <h1 class="bb-profile-header-title h3 mb-0">
                                @yield('title')
                            </h1>
                        </div>

                        <!-- Page Content -->
                        <div class="bb-profile-main">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Offcanvas -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="customerSidebar" aria-labelledby="customerSidebarLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="customerSidebarLabel">{{ __('Account Menu') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="bb-customer-sidebar-wrapper h-100 d-flex flex-column">
                <div class="bb-customer-sidebar flex-1">
                    <!-- User Profile Section -->
                    @if ($customer = auth('customer')->user())
                        <div class="bb-customer-sidebar-heading">
                            <div class="d-flex align-items-center gap-3 p-4">
                                <div class="position-relative">
                                    <div class="wrapper-image">
                                        {!! RvMedia::image($customer->avatar_url, $customer->name, attributes: ['class' => 'rounded-circle border border-2 border-white shadow-sm']) !!}
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" style="width: 12px; height: 12px;"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="name fw-semibold text-truncate">{{ $customer->name }}</div>
                                    <div class="email text-muted small text-truncate">{{ $customer->email }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Section -->
                    <nav class="bb-customer-navigation px-3 pb-4">
                        <div class="nav-section">
                            <ul class="nav nav-pills flex-column gap-1">
                                @foreach (DashboardMenu::getAll('customer') as $item)
                                    @continue(! $item['name'])
                                    <li class="nav-item">
                                        <a
                                            href="{{ $item['url'] }}"
                                            @class([
                                                'nav-link d-flex align-items-center gap-3 rounded-2 py-2 px-3',
                                                'active' => $item['active']
                                            ])
                                            title="{{ $item['name'] }}"
                                        >
                                            <x-core::icon :name="$item['icon']" class="nav-icon flex-shrink-0" size="sm" />
                                            <span class="nav-text">{{ $item['name'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
