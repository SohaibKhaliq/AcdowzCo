@php
    // Get the first order to work with for payment proof
    $order = null;
    if (isset($orders) && $orders instanceof \Illuminate\Support\Collection) {
        $order = $orders->where('is_finished', true)->first();
        if (!$order) {
            $order = $orders->first();
        }
    } elseif (isset($order) && $order instanceof \Botble\Ecommerce\Models\Order) {
        // Order is already available
    }
@endphp

@if (EcommerceHelper::isPaymentProofEnabled() && $order)
    @php
        // Determine if user is logged in as customer
        $isCustomer = auth('customer')->check();
        $guestProofEnabled = EcommerceHelper::isGuestPaymentProofEnabled();
        
        // Use token-based routes for guests (if enabled), customer routes for logged-in users
        $uploadRoute = $isCustomer 
            ? route('customer.orders.upload-proof', $order) 
            : ($guestProofEnabled ? route('public.orders.upload-proof-guest', $order->token) : null);
            
        $downloadRoute = $isCustomer 
            ? route('customer.orders.download-proof', $order) 
            : ($guestProofEnabled ? route('public.orders.download-proof-guest', $order->token) : null);
    @endphp

    @if ($order->canBeCanceled() && ($isCustomer || $guestProofEnabled))
        <div class="payment-proof-upload">
            <x-core::alert type="info" :icon="false" class="mb-0">
                <div class="w-100">
                    <div class="payment-proof-upload__header">
                        <div class="payment-proof-upload__icon">
                            <x-core::icon name="ti ti-receipt" />
                        </div>
                        <div class="payment-proof-upload__content">
                            <h6 class="payment-proof-upload__title">{{ __('Payment Proof') }}</h6>
                            <x-core::form method="post" :files="true" class="customer-order-upload-receipt" :url="$uploadRoute">
                                @if (! $order->proof_file)
                                    <p class="payment-proof-upload__message">{{ __('For expedited processing, kindly upload a copy of your payment proof:') }}</p>
                                @else
                                    <p class="payment-proof-upload__message">{{ __('You have uploaded a copy of your payment proof.') }}</p>
                                    <div class="payment-proof-upload__file-preview">
                                        <span class="payment-proof-upload__file-preview-label">{{ __('View Receipt:') }}</span>
                                        <a href="{{ $downloadRoute }}" target="_blank" class="payment-proof-upload__file-preview-link">
                                            <x-core::icon name="ti ti-file" />
                                            {{ basename($order->proof_file) }}
                                        </a>
                                    </div>
                                    <p class="payment-proof-upload__replace-text">{{ __('Or you can upload a new one, the old one will be replaced.') }}</p>
                                @endif
                                <div class="payment-proof-upload__upload-form d-block d-sm-flex">
                                    <input type="file" name="file" id="file" class="form-control">
                                    <button type="submit" class="btn payment-checkout-btn mt-2 mt-sm-0">
                                        <x-core::icon name="ti ti-upload" />
                                        {{ __('Upload') }}
                                    </button>
                                </div>
                                <small class="payment-proof-upload__help-text">{{ __('You can upload the following file types: jpg, jpeg, png, pdf and max file size is 2MB.') }}</small>
                            </x-core::form>
                        </div>
                    </div>
                </div>
            </x-core::alert>
        </div>
    @elseif ($order->proof_file && ($isCustomer || $guestProofEnabled))
        <div class="payment-proof-upload">
            <x-core::alert type="info" :icon="false" class="mb-0">
                <div class="w-100">
                    <div class="payment-proof-upload__header">
                        <div class="payment-proof-upload__icon">
                            <x-core::icon name="ti ti-receipt" />
                        </div>
                        <div class="payment-proof-upload__content">
                            <h6 class="payment-proof-upload__title">{{ __('Payment Proof') }}</h6>
                            <p class="payment-proof-upload__message">{{ __('You have uploaded a copy of your payment proof.') }}</p>
                            <div class="payment-proof-upload__file-preview">
                                <span class="payment-proof-upload__file-preview-label">{{ __('View Receipt:') }}</span>
                                <a href="{{ $downloadRoute }}" target="_blank" class="payment-proof-upload__file-preview-link">
                                    <x-core::icon name="ti ti-file" />
                                    {{ basename($order->proof_file) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </x-core::alert>
        </div>
    @endif
@endif