@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')
    <h3 class="alert-heading">{{ SeoHelper::getTitle() }}</h3>

    <div class="alert alert-warning mb-0 mt-3" role="alert">
        <p class="mb-0">{{ __('Please wait for the administrator to review and approve!') }}</p>
    </div>

    <div class="mt-4">
        <h5 class="mb-3">{{ __('Store Information') }}</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>{{ __('Store Name') }}:</strong> {{ $store->name }}</li>
            <li class="list-group-item"><strong>{{ __('Owner') }}:</strong> {{ $store->customer->name }}</li>
            <li class="list-group-item"><strong>{{ __('Phone') }}:</strong> {{ $store->phone }}</li>
            @if (MarketplaceHelper::getSetting('requires_vendor_documentations_verification', true))
                @if ($store->certificate_file && Storage::disk('local')->exists($store->certificate_file))
                    <li class="list-group-item">
                        <strong>{{ __('Uploaded Certificate') }}: </strong>
                        <a href="{{ route('marketplace.vendor.become-vendor.download-certificate') }}" target="_blank" class="text-primary">{{ __('View Certificate') }}</a>
                    </li>
                @endif
                @if ($store->government_id_file && Storage::disk('local')->exists($store->government_id_file))
                    <li class="list-group-item">
                        <strong>{{ __('Uploaded Government ID') }}: </strong>
                        <a href="{{ route('marketplace.vendor.become-vendor.download-government-id') }}" target="_blank" class="text-primary">{{ __('View Government ID') }}</a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
@endsection
