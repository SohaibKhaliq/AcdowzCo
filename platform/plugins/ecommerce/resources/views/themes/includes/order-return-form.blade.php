<div class="customer-order-detail">
    <!-- Order Information Card -->
    <div class="bb-customer-card order-info-card mb-4">
        <div class="bb-customer-card-header">
            <h3 class="bb-customer-card-title h5 mb-0">
                <x-core::icon name="ti ti-info-circle" class="me-2" />
                {{ __('Order Information') }}
            </h3>
        </div>

        <div class="bb-customer-card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="bb-customer-card-info">
                        <div class="info-item mb-3">
                            <span class="label">
                                <x-core::icon name="ti ti-hash" class="me-1" />
                                {{ __('Order Number') }}
                            </span>
                            <span class="value fw-semibold">{{ $order->code }}</span>
                        </div>

                        <div class="info-item mb-3">
                            <span class="label">
                                <x-core::icon name="ti ti-calendar" class="me-1" />
                                {{ __('Order Date') }}
                            </span>
                            <span class="value">{{ $order->created_at->translatedFormat('M d, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bb-customer-card-info">
                        <div class="info-item mb-3">
                            <span class="label">
                                <x-core::icon name="ti ti-circle-check" class="me-1" />
                                {{ __('Completed At') }}
                            </span>
                            <span class="value">{{ $order->completed_at->translatedFormat('M d, Y \a\t g:i A') }}</span>
                        </div>

                        @if (! EcommerceHelper::isDisabledPhysicalProduct() && $order->shipment && $order->shipment->id)
                            <div class="info-item mb-3">
                                <span class="label">
                                    <x-core::icon name="ti ti-truck" class="me-1" />
                                    {{ __('Shipment Status') }}
                                </span>
                                <span class="value">
                                    {!! BaseHelper::clean($order->shipment->status->toHtml()) !!}
                                </span>
                            </div>
                        @endif

                        @if (is_plugin_active('payment'))
                            <div class="info-item mb-3">
                                <span class="label">
                                    <x-core::icon name="ti ti-credit-card" class="me-1" />
                                    {{ __('Payment Status') }}
                                </span>
                                <span class="value">
                                    {!! BaseHelper::clean($order->payment->status->toHtml()) !!}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Request Form -->
    <div class="bb-customer-card return-form-card">
        <div class="bb-customer-card-header">
            <h3 class="bb-customer-card-title h5 mb-0">
                <x-core::icon name="ti ti-package-export" class="me-2" />
                {{ __('Return Request Form') }}
            </h3>
        </div>

        <div class="bb-customer-card-body">
            {!! Form::open(['url' => route('customer.order_returns.send_request'), 'method' => 'POST']) !!}
            {!! Form::hidden('order_id', $order->id) !!}

            @if (!EcommerceHelper::allowPartialReturn())
                <div class="return-reason-section mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold" for="reason">
                                    <x-core::icon name="ti ti-info-circle" class="me-1" />
                                    {{ __('Return Reason') }}
                                    <span class="text-danger">*</span>
                                </label>
                                {!! Form::select('reason', array_filter(Botble\Ecommerce\Enums\OrderReturnReasonEnum::labels()), old('reason'), [
                                    'class' => 'order-return-reason-select form-select',
                                    'placeholder' => __('Choose Reason'),
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Products Selection Section -->
            <div class="products-selection-section">
                <h6 class="section-title mb-3">
                    <x-core::icon name="ti ti-package" class="me-2" />
                    {{ __('Select Products to Return') }}
                </h6>

                @php
                    $totalRefundAmount = $order->amount - $order->shipping_amount;
                    $totalPriceProducts = $order->products->sum(function ($item) {
                        return $item->total_price_with_tax;
                    });
                    $ratio = $totalRefundAmount <= 0 ? 0 : $totalPriceProducts / $totalRefundAmount;
                @endphp

                <div class="bb-customer-card-list return-products-list">
                    @foreach ($order->products as $key => $orderProduct)
                        <div class="bb-customer-card return-product-card">
                            <div class="bb-customer-card-body">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Selection Checkbox -->
                                    <div class="flex-shrink-0">
                                        <div class="form-check">
                                            {!! Form::checkbox(
                                                'return_items[' . $key . '][is_return]',
                                                $orderProduct->id,
                                                old('return_items.' . $key . '.is_return', true),
                                                [
                                                    'class' => 'form-check-input',
                                                    'id' => 'return_item_' . $key,
                                                ],
                                            ) !!}
                                            <input
                                                name="return_items[{{ $key }}][order_item_id]"
                                                type="hidden"
                                                value="{{ $orderProduct->id }}"
                                            >
                                        </div>
                                    </div>

                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="return-product-image">
                                            <img
                                                src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                                alt="{{ $orderProduct->product_name }}"
                                                class="img-fluid rounded"
                                            >
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-grow-1">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="return-product-details">
                                                    <label for="return_item_{{ $key }}" class="return-product-name mb-2 cursor-pointer">
                                                        {{ $orderProduct->product_name }}
                                                    </label>

                                                    @if ($sku = Arr::get($orderProduct->options, 'sku'))
                                                        <div class="return-product-sku mb-1">
                                                            <small class="text-muted">
                                                                <x-core::icon name="ti ti-barcode" class="me-1" />
                                                                {{ __('SKU') }}: {{ $sku }}
                                                            </small>
                                                        </div>
                                                    @endif

                                                    @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                                        <div class="return-product-attributes mb-2">
                                                            <small class="text-muted">{{ $attributes }}</small>
                                                        </div>
                                                    @endif

                                                    <div class="return-product-price">
                                                        <span class="fw-semibold text-primary">{{ format_price($orderProduct->price_with_tax) }}</span>
                                                        <small class="text-muted">{{ __('per item') }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="return-product-controls">
                                                    @if (EcommerceHelper::allowPartialReturn())
                                                        <div class="row g-2 mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label small fw-semibold">
                                                                    <x-core::icon name="ti ti-package" class="me-1" />
                                                                    {{ __('Quantity') }}
                                                                </label>
                                                                @php
                                                                    $qtyChooses = [];
                                                                    for ($i = 1; $i <= $orderProduct->qty; $i++) {
                                                                        $qtyChooses[$i] = [
                                                                            'data-qty' => $i,
                                                                            'data-amount' => format_price($ratio == 0 ? 0 : ($orderProduct->price_with_tax * $i) / $ratio),
                                                                        ];
                                                                    }
                                                                @endphp
                                                                {!! Form::select(
                                                                    'return_items[' . $key . '][qty]',
                                                                    collect($qtyChooses)->pluck('data-qty', 'data-qty'),
                                                                    old('return_items.' . $key . '.qty', $orderProduct->qty),
                                                                    [
                                                                        'class' => 'form-select form-select-sm select-return-item-qty',
                                                                    ],
                                                                    $qtyChooses,
                                                                ) !!}
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label small fw-semibold">
                                                                    <x-core::icon name="ti ti-currency-dollar" class="me-1" />
                                                                    {{ __('Refund Amount') }}
                                                                </label>
                                                                <div class="form-control form-control-sm bg-light">
                                                                    <span class="return-amount fw-semibold text-success">
                                                                        {{ format_price($ratio == 0 ? 0 : $orderProduct->total_price_with_tax / $ratio) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label small fw-semibold">
                                                                <x-core::icon name="ti ti-info-circle" class="me-1" />
                                                                {{ __('Return Reason') }}
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            {!! Form::select(
                                                                'return_items[' . $key . '][reason]',
                                                                array_filter(Botble\Ecommerce\Enums\OrderReturnReasonEnum::labels()),
                                                                old('return_items.' . $key . '.reason'),
                                                                [
                                                                    'class' => 'form-select form-select-sm',
                                                                    'placeholder' => __('Choose Reason'),
                                                                ],
                                                            ) !!}
                                                        </div>
                                                    @else
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <div class="info-item text-center">
                                                                    <span class="label">
                                                                        <x-core::icon name="ti ti-package" class="me-1" />
                                                                        {{ __('Quantity') }}
                                                                    </span>
                                                                    <span class="value fw-semibold">{{ $orderProduct->qty }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="info-item text-center">
                                                                    <span class="label">
                                                                        <x-core::icon name="ti ti-currency-dollar" class="me-1" />
                                                                        {{ __('Refund Amount') }}
                                                                    </span>
                                                                    <span class="value fw-semibold text-success">
                                                                        {{ format_price($ratio == 0 ? 0 : $orderProduct->total_price_with_tax / $ratio) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bb-customer-card-footer">
                @if ($order->canBeReturned())
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <x-core::icon name="ti ti-info-circle" class="me-1" />
                            {{ __('Please review your selection before submitting the return request.') }}
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <x-core::icon name="ti ti-send" class="me-1" />
                            {{ __('Submit Return Request') }}
                        </button>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <x-core::icon name="ti ti-alert-triangle" class="me-1" />
                        {{ __('This order cannot be returned at this time.') }}
                    </div>
                @endif
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
