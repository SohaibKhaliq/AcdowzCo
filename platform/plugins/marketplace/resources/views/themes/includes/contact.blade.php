@if (MarketplaceHelper::isEnabledMessagingSystem() && (! auth('customer')->check() || $store->id != auth('customer')->user()->store?->id))
    <div class="mb-4 row">
        <div class="col-md-6">
            <h3 class="fs-4">{{ __('Email :store', ['store' => $store->name]) }}</h3>
            <p>{{ __('All messages are recorded and spam is not tolerated. Your email address will be shown to the recipient.') }}</p>
            {!! $contactForm->renderForm() !!}
        </div>
    </div>

    @include(MarketplaceHelper::viewPath('includes.contact-form-script'))
@endif
