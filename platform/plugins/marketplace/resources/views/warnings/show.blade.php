@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('Warning Details') }}
                    </x-core::card.title>
                    <div class="card-actions">
                        <x-core::badge 
                            :color="match($warning->severity->value ?? $warning->severity) {
                                'critical' => 'danger',
                                'warning' => 'warning',
                                'notice' => 'info',
                                default => 'secondary'
                            }"
                        >
                            {{ ucfirst($warning->severity) }}
                        </x-core::badge>
                    </div>
                </x-core::card.header>

                <x-core::card.body>
                    <h4>{{ $warning->title }}</h4>
                    
                    <div class="mb-4">
                        <p class="text-secondary mb-2">{{ trans('Message:') }}</p>
                        <div class="p-3 bg-blue-lt rounded border border-blue-lt">
                            {{ $warning->content }}
                        </div>
                    </div>

                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ trans('Issued by') }}</div>
                            <div class="datagrid-content">{{ $warning->issuedBy->name ?? 'System' }}</div>
                        </div>

                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ trans('Date issued') }}</div>
                            <div class="datagrid-content">{{ $warning->created_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>

                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ trans('Email sent') }}</div>
                            <div class="datagrid-content">
                                @if($warning->email_sent)
                                    <span class="text-success"><x-core::icon name="ti ti-check" /> {{ trans('Yes') }}</span>
                                @else
                                    <span class="text-secondary"><x-core::icon name="ti ti-x" /> {{ trans('No') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ trans('Acknowledged') }}</div>
                            <div class="datagrid-content">
                                @if($warning->acknowledged)
                                    <span class="text-success">
                                        <x-core::icon name="ti ti-check" />
                                        {{ trans('Yes') }} - {{ $warning->acknowledged_at->format('F j, Y \a\t g:i A') }}
                                    </span>
                                @else
                                    <span class="text-warning"><x-core::icon name="ti ti-clock" /> {{ trans('Pending') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-core::card.body>

                <x-core::card.footer>
                    <x-core::button
                        tag="a"
                        :href="route('marketplace.vendor-warnings.index')"
                        color="secondary"
                    >
                        {{ trans('Back to List') }}
                    </x-core::button>
                </x-core::card.footer>
            </x-core::card>
        </div>

        <div class="col-md-4">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('Store Information') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body>
                    <div class="text-center mb-3">
                        @if($warning->store->logo)
                            <img
                                src="{{ RvMedia::getImageUrl($warning->store->logo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $warning->store->name }}"
                                class="avatar avatar-rounded avatar-lg"
                            />
                        @endif
                    </div>

                    <h5 class="text-center mb-3">{{ $warning->store->name }}</h5>

                    <dl class="row">
                        <dt class="col-5">{{ trans('Email:') }}</dt>
                        <dd class="col-7">
                            <a href="mailto:{{ $warning->store->email }}">{{ $warning->store->email }}</a>
                        </dd>

                        <dt class="col-5">{{ trans('Phone:') }}</dt>
                        <dd class="col-7">{{ $warning->store->phone }}</dd>

                        <dt class="col-5">{{ trans('Vendor:') }}</dt>
                        <dd class="col-7">
                            <a href="{{ route('customers.edit', $warning->store->customer_id) }}" target="_blank">
                                {{ $warning->store->customer->name }}
                                <x-core::icon name="ti ti-external-link" size="sm" />
                            </a>
                        </dd>
                    </dl>

                    <div class="mt-3">
                        <x-core::button
                            tag="a"
                            :href="route('marketplace.store.edit', $warning->store_id)"
                            color="primary"
                            class="w-100"
                            target="_blank"
                        >
                            {{ trans('View Store') }}
                            <x-core::icon name="ti ti-external-link" />
                        </x-core::button>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@endsection
