@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Order information'))

@section('content')
    <div class="customer-order-detail">
        <div class="bb-order-header mb-4">
            <h2 class="bb-order-title">{{ __('Order Details') }}</h2>
            <div class="bb-order-actions d-flex flex-wrap gap-2 mt-3">
                @if($order->shipment->can_confirm_delivery)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmDeliveredModal" data-toggle="modal" data-target="#confirmDeliveredModal">
                        <x-core::icon name="ti ti-check" />
                        {{ __('Confirm Delivery') }}
                    </button>
                @endif
                @if ($order->isInvoiceAvailable())
                    <a class="btn btn-success" href="{{ route('customer.print-order', $order->id) }}?type=print" target="_blank">
                        <x-core::icon name="ti ti-printer" />
                        {{ __('Print invoice') }}
                    </a>
                    <a class="btn btn-success" href="{{ route('customer.print-order', $order->id) }}">
                        <x-core::icon name="ti ti-download" />
                        {{ __('Download invoice') }}
                    </a>
                @endif
                @if ($order->canBeCanceled())
                    <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-cancel-order" data-toggle="modal" data-target="#modal-cancel-order">
                        <x-core::icon name="ti ti-x" />
                        {{ __('Cancel order') }}
                    </a>
                @endif
                @if ($order->canBeReturned())
                    <a class="btn btn-danger" href="{{ route('customer.order_returns.request_view', $order->id) }}">
                        <x-core::icon name="ti ti-arrow-back-up" />
                        {{ __('Return Product(s)') }}
                    </a>
                @endif
            </div>
        </div>

        @include(EcommerceHelper::viewPath('includes.order-tracking-detail'))

        @if (EcommerceHelper::isPaymentProofEnabled())
            @if ($order->canBeCanceled())
                <div class="bb-payment-proof-card">
                    <div class="bb-payment-proof-card-content">
                        <div class="bb-payment-proof-card-icon">
                            <x-core::icon name="ti ti-receipt" class="payment-icon" />
                        </div>
                        <div class="bb-payment-proof-card-details">
                            <h5 class="bb-payment-proof-card-title">{{ __('Payment Proof') }}</h5>
                            <x-core::form method="post" :files="true" class="customer-order-upload-receipt" :url="route('customer.orders.upload-proof', $order)">
                                <div class="bb-payment-proof-card-content-inner">
                                    <div class="bb-payment-proof-card-message">
                                        @if (! $order->proof_file)
                                            <p class="mb-3">{{ __('The order is currently being processed. For expedited processing, kindly upload a copy of your payment proof:') }}</p>
                                        @else
                                            <p class="mb-2">{{ __('You have uploaded a copy of your payment proof.') }}</p>
                                            <div class="bb-payment-proof-card-file mb-3 bg-light">
                                                <span class="label">{{ __('View Receipt:') }}</span>
                                                <a href="{{ route('customer.orders.download-proof', $order) }}" target="_blank" class="value">
                                                    <x-core::icon name="ti ti-file" />
                                                    {{ $order->proof_file }}
                                                </a>
                                            </div>
                                            <p class="mb-3 fw-medium">{{ __('Or you can upload a new one, the old one will be replaced.') }}</p>
                                        @endif
                                    </div>
                                    <div class="bb-payment-proof-card-upload">
                                        <div class="input-group">
                                            <input type="file" name="file" id="file" class="form-control">
                                            <button type="submit" class="btn btn-primary">
                                                <x-core::icon name="ti ti-upload" />
                                                {{ __('Upload') }}
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-2">{{ __('You can upload the following file types: jpg, jpeg, png, pdf and max file size is 2MB.') }}</small>
                                    </div>
                                </div>
                            </x-core::form>
                        </div>
                    </div>
                </div>
            @elseif ($order->proof_file)
                <div class="bb-payment-proof-card">
                    <div class="bb-payment-proof-card-content">
                        <div class="bb-payment-proof-card-icon">
                            <x-core::icon name="ti ti-receipt" class="payment-icon" />
                        </div>
                        <div class="bb-payment-proof-card-details">
                            <h5 class="bb-payment-proof-card-title">{{ __('Payment Proof') }}</h5>
                            <div class="bb-payment-proof-card-content-inner">
                                <div class="bb-payment-proof-card-message">
                                    <p class="mb-2">{{ __('You have uploaded a copy of your payment proof.') }}</p>
                                    <div class="bb-payment-proof-card-file">
                                        <span class="label">{{ __('View Receipt:') }}</span>
                                        <a href="{{ route('customer.orders.download-proof', $order) }}" target="_blank" class="value">
                                            <x-core::icon name="ti ti-file" />
                                            {{ $order->proof_file }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    @if ($order->canBeCanceled())
        <div class="modal fade" id="modal-cancel-order" tabindex="-1" aria-labelledby="modalCancelOrderLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header align-items-start">
                        <div>
                            <h4 class="modal-title fs-5" id="modalCancelOrderLabel">{{ __('Cancel Order') }}</h4>
                            <p class="text-muted mb-0">{{ __('Please provide a reason for the cancellation.') }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {!! $cancelOrderForm->renderForm() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary" form="cancel-order-form">{{ __('Submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($order->shipment->can_confirm_delivery)
        <div class="modal fade" id="confirmDeliveredModal" tabindex="-1" aria-labelledby="confirmDeliveredModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 1rem; inset-inline-end: 1rem; z-index: 1000"></button>
                    <form action="{{ route('customer.orders.confirm-delivery', $order) }}" method="post" class="modal-body text-center my-3">
                        @csrf
                        <h5 class="modal-title fs-5 mb-2" id="confirmDeliveredModalLabel">{{ __('Confirm Delivery') }}</h5>
                        <p class="mb-4">{{ __('Are you sure you have received your order? Please confirm that the package has been delivered to you?') }}</p>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="w-50 btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="w-50 btn btn-primary">{{ __('Confirm') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop
