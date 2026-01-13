@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row row-cards">
        <div class="col-md-3">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/marketplace::revenue.store_information') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body class="p-0">
                    <div class="text-center p-3">
                        <div class="mb-2">
                            <img
                                src="{{ RvMedia::getImageUrl($store->logo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                alt="{{ $store->name }}"
                                class="avatar avatar-rounded avatar-xl"
                            />
                        </div>

                        <a href="{{ $store->url }}" target="_blank">
                            {{ $store->name }}
                            <x-core::icon name="ti ti-external-link" />
                        </a>
                    </div>

                    <div class="hr my-2"></div>

                    <div class="p-3">
                        <dl class="row">
                            <dt class="col">{{ trans('plugins/marketplace::revenue.vendor_name') }}</dt>
                            <dd class="col-auto">
                                <a href="{{ route('customers.edit', $customer->id) }}" target="_blank">
                                    {{ $customer->name }}
                                    <x-core::icon name="ti ti-external-link" />
                                </a>
                            </dd>
                        </dl>
                        <dl class="row">
                            <dt class="col">{{ trans('plugins/marketplace::revenue.balance') }}</dt>
                            <dd class="col-auto">
                            <span class="vendor-balance">
                                {{ format_price($customer->balance) }}
                                <a
                                    data-bs-toggle="modal"
                                    data-bs-target="#update-balance-modal"
                                    href="javascript:void(0)"
                                    class="text-decoration-none"
                                >
                                    <x-core::icon name="ti ti-edit" />
                                </a>
                            </span>
                            </dd>
                        </dl>

                        <dl class="row">
                            <dt class="col">{{ trans('plugins/marketplace::revenue.products') }}</dt>
                            <dd class="col-auto">{{ number_format($store->products()->count()) }}</dd>
                        </dl>
                    </div>
                </x-core::card.body>
            </x-core::card>

            <div class="card mt-3">
                @if($store->is_verified)
                    <div class="card-status-top bg-success"></div>
                @else
                    <div class="card-status-top bg-warning"></div>
                @endif

                <div class="card-header">
                    <h3 class="card-title">
                        <x-core::icon name="ti ti-shield-check" />
                        {{ trans('plugins/marketplace::store.forms.verification_section') }}
                    </h3>
                </div>

                <div class="card-body">
                    @if($store->is_verified)
                        <div class="alert alert-success" role="alert">
                            <div class="d-flex gap-2">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">{{ trans('plugins/marketplace::store.verified') }}</h4>
                                    <div class="text-secondary">{{ trans('plugins/marketplace::store.store_verified_successfully') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="datagrid">
                                    @if ($store->verifiedBy)
                                        <div class="datagrid-item">
                                            <div class="datagrid-title">{{ trans('plugins/marketplace::store.verified_by') }}</div>
                                            <div class="datagrid-content">
                                                <strong>{{ $store->verifiedBy->name }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">{{ trans('plugins/marketplace::store.verified_at') }}</div>
                                        <div class="datagrid-content">
                                            {{ $store->verified_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($store->verification_note)
                                <div class="col-12">
                                    <div class="card bg-blue-lt">
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <x-core::icon name="ti ti-notes" />
                                                {{ trans('plugins/marketplace::store.forms.verification_note') }}
                                            </h4>
                                            <p class="text-secondary mb-0">{{ $store->verification_note }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#unverify-store-modal">
                                <x-core::icon name="ti ti-shield-x" />
                                {{ trans('plugins/marketplace::store.unverify_store') }}
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">{{ trans('plugins/marketplace::store.not_verified') }}</h4>
                                    <div class="text-secondary">{{ trans('plugins/marketplace::store.store_not_verified_yet') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"></path>
                                <circle cx="12" cy="11" r="1"></circle>
                                <line x1="12" y1="12" x2="12" y2="14.5"></line>
                            </svg>
                            <h3>{{ trans('plugins/marketplace::store.verification_pending') }}</h3>
                            <p class="text-muted">{{ trans('plugins/marketplace::store.click_verify_to_approve') }}</p>

                            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#verify-store-modal">
                                <x-core::icon name="ti ti-shield-check" />
                                {{ trans('plugins/marketplace::store.verify_store') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/marketplace::revenue.statements') }}
                    </x-core::card.title>
                    <x-core::card.actions>
                        <a
                            data-bs-toggle="modal"
                            data-bs-target="#update-balance-modal"
                            href="javascript:void(0)"
                            class="small"
                        >
                            <x-core::icon name="ti ti-edit" />
                            {{ trans('plugins/marketplace::revenue.update_balance') }}
                        </a>
                    </x-core::card.actions>
                </x-core::card.header>

                {!! $table->renderTable() !!}
            </x-core::card>
        </div>
    </div>
@endsection

@push('footer')
    <x-core::modal
        id="update-balance-modal"
        :title="trans('plugins/marketplace::revenue.update_balance_title')"
        button-id="confirm-update-amount-button"
        :button-label="trans('core/base::tables.submit')"
        size="md"
    >
        <x-core::form :url="route('marketplace.store.revenue.create', $store->id)">
            <x-core::form.text-input
                :label="trans('plugins/marketplace::revenue.forms.amount')"
                name="amount"
                type="number"
                :placeholder="trans('plugins/marketplace::revenue.forms.amount_placeholder')"
                :group-flat="true"
            >
                <x-slot:prepend>
                    <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                </x-slot:prepend>
            </x-core::form.text-input>

            <x-core::form.radio-list
                :label="trans('plugins/marketplace::revenue.forms.type')"
                name="type"
                :options="Botble\Marketplace\Enums\RevenueTypeEnum::adjustLabels()"
                :value="Botble\Marketplace\Enums\RevenueTypeEnum::ADD_AMOUNT"
            />

            <x-core::form.textarea
                :label="trans('core/base::forms.description')"
                name="description"
                :placeholder="trans('plugins/marketplace::revenue.forms.description_placeholder')"
                rows="3"
            />
        </x-core::form>
    </x-core::modal>

    @if(!$store->is_verified)
        <x-core::modal
            id="verify-store-modal"
            :title="trans('plugins/marketplace::store.verify_store_confirmation')"
            button-id="confirm-verify-button"
            :button-label="trans('plugins/marketplace::store.verify_store')"
            button-class="btn-success"
            size="md"
        >
            <x-core::form :url="route('marketplace.store.verify', $store->id)">
                <div class="alert alert-info" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                <polyline points="11 12 12 12 12 16 13 16"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h4 class="alert-title">{{ trans('plugins/marketplace::store.verify_store_confirmation') }}</h4>
                            <div class="text-secondary">{{ trans('plugins/marketplace::store.verify_store_confirmation_desc', ['name' => $store->name]) }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <x-core::icon name="ti ti-notes" />
                        {{ trans('plugins/marketplace::store.forms.verification_note') }}
                    </label>
                    <textarea
                        class="form-control"
                        name="verification_note"
                        rows="3"
                        placeholder="{{ trans('plugins/marketplace::store.forms.verification_note_placeholder') }}"
                    ></textarea>
                    <small class="form-hint">{{ trans('plugins/marketplace::store.forms.verification_note_helper') }}</small>
                </div>
            </x-core::form>
        </x-core::modal>
    @else
        <x-core::modal
            id="unverify-store-modal"
            :title="trans('plugins/marketplace::store.unverify_store_confirmation')"
            button-id="confirm-unverify-button"
            :button-label="trans('plugins/marketplace::store.unverify_store')"
            button-class="btn-warning"
            size="md"
        >
            <x-core::form :url="route('marketplace.store.unverify', $store->id)">
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="alert-title">{{ trans('plugins/marketplace::store.unverify_store_confirmation') }}</h4>
                            <div class="text-secondary">{{ trans('plugins/marketplace::store.unverify_store_confirmation_desc', ['name' => $store->name]) }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <x-core::icon name="ti ti-notes" />
                        {{ trans('plugins/marketplace::store.forms.verification_note') }}
                    </label>
                    <textarea
                        class="form-control"
                        name="verification_note"
                        rows="3"
                        placeholder="{{ trans('plugins/marketplace::store.forms.verification_note_placeholder') }}"
                    ></textarea>
                    <small class="form-hint">{{ trans('plugins/marketplace::store.forms.verification_note_helper') }}</small>
                </div>
            </x-core::form>
        </x-core::modal>
    @endif
@endpush
