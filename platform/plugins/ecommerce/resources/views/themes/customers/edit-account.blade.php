@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Account Settings'))

@section('content')
    <div class="bb-customer-card-list account-settings-cards">
        {{-- Profile Information Card --}}
        <div class="bb-customer-card profile-card">
            <div class="bb-customer-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                        <x-core::icon name="ti ti-user" class="text-primary" />
                    </div>
                    <div>
                        <h3 class="bb-customer-card-title h5 mb-1">{{ __('Profile Information') }}</h3>
                        <p class="text-muted small mb-0">{{ __('Update your account profile information and email address.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bb-customer-card-body">
                {!! $form->renderForm() !!}
            </div>
        </div>

        {{-- Change Password Card --}}
        <div class="bb-customer-card password-card">
            <div class="bb-customer-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                        <x-core::icon name="ti ti-lock" class="text-warning" />
                    </div>
                    <div>
                        <h3 class="bb-customer-card-title h5 mb-1">{{ __('Change Password') }}</h3>
                        <p class="text-muted small mb-0">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bb-customer-card-body">
                {!! $passwordForm->renderForm() !!}
            </div>
        </div>

        {{-- Delete Account Card --}}
        @if (get_ecommerce_setting('enabled_customer_account_deletion', true))
            <div class="bb-customer-card delete-account-card">
                <div class="bb-customer-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                            <x-core::icon name="ti ti-trash" class="text-danger" />
                        </div>
                        <div>
                            <h3 class="bb-customer-card-title h5 mb-1 text-danger">{{ __('Delete Account') }}</h3>
                            <p class="text-muted small mb-0">{{ __('Permanently delete your account and all associated data.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bb-customer-card-body">
                    <div class="alert alert-warning d-flex align-items-start gap-3" role="alert">
                        <x-core::icon name="ti ti-alert-triangle" class="text-warning flex-shrink-0 mt-1" />
                        <div>
                            <h6 class="alert-heading mb-1">{{ __('Warning') }}</h6>
                            <p class="mb-0 small">
                                {{ __('This action will permanently delete your account and all associated data and is irreversible. Please be sure before proceeding.') }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#delete-account-modal"
                        data-toggle="modal"
                        data-target="#delete-account-modal"
                    >
                        <x-core::icon name="ti ti-trash" class="me-1" />
                        {{ __('Delete your account') }}
                    </button>
                </div>
            </div>

            {{-- Delete Account Modal --}}
            <div class="modal fade" id="delete-account-modal" tabindex="-1" aria-labelledby="delete-account-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                                    <x-core::icon name="ti ti-alert-triangle" class="text-danger" />
                                </div>
                                <div>
                                    <h4 class="modal-title h5 mb-0" id="delete-account-modal-title">
                                        {{ __('Delete Account') }}
                                    </h4>
                                    <p class="text-muted small mb-0">{{ __('This action cannot be undone') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <div class="alert alert-danger d-flex align-items-start gap-3" role="alert">
                                <x-core::icon name="ti ti-info-circle" class="text-danger flex-shrink-0 mt-1" />
                                <div>
                                    <p class="mb-0 small">
                                        {{ __('We will send you an email to confirm your account deletion. Once you confirm, your account will be deleted permanently and all your data will be lost.') }}
                                    </p>
                                </div>
                            </div>

                            <x-core::form :url="route('customer.delete-account.store')" method="post">
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">{{ __('Confirm your password') }}</label>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="{{ __('Enter your current password') }}"
                                        required
                                    >
                                </div>
                                <div class="mb-4">
                                    <label for="reason" class="form-label fw-semibold">{{ __('Reason (optional)') }}</label>
                                    <textarea
                                        id="reason"
                                        name="reason"
                                        class="form-control"
                                        rows="3"
                                        placeholder="{{ __('Tell us why you want to delete your account...') }}"
                                    ></textarea>
                                </div>
                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal" data-dismiss="modal">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-danger flex-fill">
                                        <x-core::icon name="ti ti-trash" class="me-1" />
                                        {{ __('Delete Account') }}
                                    </button>
                                </div>
                            </x-core::form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
