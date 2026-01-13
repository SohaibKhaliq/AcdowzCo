@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Overview'))

@section('content')
    @php
        $customer = auth('customer')->user();
        EcommerceHelper::registerThemeAssets();
    @endphp

    <!-- Welcome Section -->
    <div class="bb-customer-profile-wrapper">
        <div class="bb-customer-profile">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="bb-customer-profile-avatar">
                        {{ RvMedia::image($customer->avatar_url, $customer->name, attributes: ['class' => 'bb-customer-profile-avatar-img', 'data-bb-value' => 'customer-avatar']) }}
                        <div class="bb-customer-profile-avatar-overlay">
                            <input type="file" id="customer-avatar" name="avatar" data-bb-toggle="change-customer-avatar" data-url="{{ route('customer.avatar') }}" />
                            <label for="customer-avatar" title="{{ __('Change avatar') }}">
                                <x-core::icon name="ti ti-camera" />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="bb-customer-profile-info text-md-center text-start">
                        <h2 class="h4 mb-2">
                            {!! BaseHelper::clean(__('Welcome back, <strong>:name</strong>!', ['name' => $customer->name])) !!}
                        </h2>
                        <p class="text-muted mb-0">
                            {{ __('Manage your account, view orders, and update your preferences from your personal dashboard.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-20 rounded-circle p-3 d-inline-flex mb-3">
                        <x-core::icon name="ti ti-shopping-bag" class="text-white" size="lg" />
                    </div>
                    <h5 class="card-title h6 mb-2">{{ __('View Orders') }}</h5>
                    <p class="card-text text-muted small mb-3">{{ __('Track your recent orders and order history') }}</p>
                    <a href="{{ route('customer.orders') }}" class="btn btn-primary btn-sm">
                        {{ __('View Orders') }}
                        <x-core::icon name="ti ti-arrow-right" class="ms-1" />
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-20 rounded-circle p-3 d-inline-flex mb-3">
                        <x-core::icon name="ti ti-map-pin" class="text-white" size="lg" />
                    </div>
                    <h5 class="card-title h6 mb-2">{{ __('Manage Addresses') }}</h5>
                    <p class="card-text text-muted small mb-3">{{ __('Update your shipping and billing addresses') }}</p>
                    <a href="{{ route('customer.address') }}" class="btn btn-success btn-sm">
                        {{ __('Manage Addresses') }}
                        <x-core::icon name="ti ti-arrow-right" class="ms-1" />
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-20 rounded-circle p-3 d-inline-flex mb-3">
                        <x-core::icon name="ti ti-settings" class="text-white" size="lg" />
                    </div>
                    <h5 class="card-title h6 mb-2">{{ __('Account Settings') }}</h5>
                    <p class="card-text text-muted small mb-3">{{ __('Edit your profile and account details') }}</p>
                    <a href="{{ route('customer.edit-account') }}" class="btn btn-warning btn-sm">
                        {{ __('Edit Account') }}
                        <x-core::icon name="ti ti-arrow-right" class="ms-1" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- First Order Prompt -->
    @if (! $customer->orders()->exists())
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-md text-center text-md-start">
                        <span class="bg-info bg-opacity-20 rounded-circle p-3 mb-3 mb-md-0 d-inline-block">
                            <x-core::icon name="ti ti-shopping-cart" class="text-white" size="lg" />
                        </span>
                    </div>
                    <div class="col-12 col-md text-center text-md-start">
                        <h5 class="card-title h6 mb-1">{{ __('Ready to start shopping?') }}</h5>
                        <p class="card-text text-muted small mb-3 mb-md-0">
                            {{ __("You haven't placed any orders yet. Browse our products and find something you love!") }}
                        </p>
                    </div>
                    <div class="col-12 col-md-auto">
                        <a href="{{ route('public.products') }}" class="btn btn-info w-100 w-md-auto">
                            <x-core::icon name="ti ti-shopping-bag" class="me-1" />
                            {{ __('Browse Products') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
