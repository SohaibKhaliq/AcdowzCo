@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::form :url="route('marketplace.vendor-warnings.store')" method="post">
        <x-core::card>
            <x-core::card.header>
                <x-core::card.title>
                    {{ trans('Issue Vendor Warning') }}
                </x-core::card.title>
            </x-core::card.header>

            <x-core::card.body>
                <x-core::form.select
                    name="store_id"
                    :label="trans('Select Store')"
                    :options="$stores->toArray()"
                    :value="old('store_id', $store?->id)"
                    :required="true"
                    :placeholder="trans('-- Select Store --')"
                    :searchable="true"
                />

                <x-core::form.text-input
                    name="title"
                    :label="trans('Warning Title')"
                    :value="old('title')"
                    :required="true"
                    :placeholder="trans('Enter warning title')"
                />

                <x-core::form.select
                    name="severity"
                    :label="trans('Severity Level')"
                    :options="[
                        'notice' => trans('Notice'),
                        'warning' => trans('Warning'),
                        'critical' => trans('Critical'),
                    ]"
                    :value="old('severity', 'warning')"
                    :required="true"
                />

                <x-core::form.textarea
                    name="content"
                    :label="trans('Warning Message')"
                    :value="old('content')"
                    :required="true"
                    rows="6"
                    :placeholder="trans('Enter detailed warning message...')"
                />

                <x-core::form.checkbox
                    name="send_email"
                    :label="trans('Send Email Notification')"
                    :checked="old('send_email', true)"
                    :helper-text="trans('Send an email notification to the vendor')"
                />
            </x-core::card.body>

            <x-core::card.footer>
                <div class="d-flex justify-content-between">
                    <x-core::button
                        tag="a"
                        :href="route('marketplace.vendor-warnings.index')"
                        color="secondary"
                    >
                        {{ trans('Cancel') }}
                    </x-core::button>
                    
                    <x-core::button
                        type="submit"
                        color="primary"
                        icon="ti ti-check"
                    >
                        {{ trans('Issue Warning') }}
                    </x-core::button>
                </div>
            </x-core::card.footer>
        </x-core::card>
    </x-core::form>
@endsection
