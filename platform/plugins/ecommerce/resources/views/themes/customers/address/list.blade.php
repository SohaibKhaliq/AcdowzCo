@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Address books'))

@section('content')
    @if($addresses->isNotEmpty())
        <div class="dashboard-address">
            <!-- Page Header -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                <div>
                    <h2 class="h5 mb-1">{{ __('Your Addresses') }}</h2>
                    <p class="text-muted small mb-0">{{ __('Manage your shipping and billing addresses') }}</p>
                </div>
            </div>

            <!-- Address Grid -->
            <div class="bb-customer-card-list">
                <div class="row row-cols-1 row-cols-lg-2 g-4">
                    @foreach ($addresses as $address)
                        @include(EcommerceHelper::viewPath('customers.address.item'), ['address' => $address])
                    @endforeach
                </div>
            </div>

            <!-- Add Address Prompt -->
            @if($addresses->count() < 5)
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body text-center py-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                            <x-core::icon name="ti ti-map-pin-plus" class="text-primary" />
                        </div>
                        <h5 class="card-title h6 mb-2">{{ __('Need another address?') }}</h5>
                        <p class="card-text text-muted small mb-3">
                            {{ __('Add multiple addresses for different shipping locations or billing purposes.') }}
                        </p>
                        <a href="{{ route('customer.address.create') }}" class="btn btn-outline-primary btn-sm">
                            <x-core::icon name="ti ti-plus" class="me-1" />
                            {{ __('Add Another Address') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @else
        @include(EcommerceHelper::viewPath('customers.partials.empty-state'), [
            'title' => __('No addresses yet!'),
            'subtitle' => __('Add your first shipping or billing address to get started.'),
            'actionUrl' => route('customer.address.create'),
            'actionLabel' => __('Add Your First Address'),
        ])
    @endif
@endsection
