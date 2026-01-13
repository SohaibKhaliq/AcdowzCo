@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    <div id="main-order-content">
        @include('plugins/ecommerce::orders.partials.canceled-alert', compact('order'))

        {!! apply_filters('ecommerce_order_detail_top', null, $order) !!}

        <div class="row row-cards">
            <div class="col-md-8">
                <x-core::card class="mb-3">
                    @include('plugins/ecommerce::orders.edit.order-header')

                    @include('plugins/ecommerce::orders.edit.order-products', [
                        'isInAdmin' => false,
                        'editProductRoute' => 'marketplace.vendor.products.edit',
                   ])

                    <x-core::card.body>
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                @include('plugins/ecommerce::orders.edit.order-info', [
                                    'isInAdmin' => false,
                                    'proofDownloadUrl' => route('marketplace.vendor.orders.download-proof', $order->id),
                                ])

                                @if ($order->isInvoiceAvailable())
                                    <div class="text-end my-3">
                                        <x-core::button
                                            tag="a"
                                            :href="route('marketplace.vendor.orders.generate-invoice', $order->id)"
                                            target="_blank"
                                            icon="ti ti-download"
                                        >
                                            {{ trans('plugins/ecommerce::order.download_invoice') }}
                                        </x-core::button>
                                    </div>
                                @endif

                                @include('plugins/ecommerce::orders.edit.form-edit', ['route' => 'marketplace.vendor.orders.edit'])
                            </div>
                        </div>
                    </x-core::card.body>

                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="text-uppercase">
                            <x-core::icon name="ti ti-check" @class(['text-success' => $order->is_confirmed]) />

                            @if ($order->is_confirmed)
                                {{ trans('plugins/ecommerce::order.order_was_confirmed') }}
                            @else
                                {{ trans('plugins/ecommerce::order.confirm_order') }}
                            @endif
                        </div>

                        @if (!$order->is_confirmed)
                            <x-core::form :url="route('marketplace.vendor.orders.confirm')">
                                <input name="order_id" type="hidden" value="{{ $order->id }}">
                                <x-core::button type="button" color="info" class="btn-confirm-order">
                                    {{ trans('plugins/ecommerce::order.confirm') }}
                                </x-core::button>
                            </x-core::form>
                        @endif
                    </div>

                    @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-start gap-1">
                                <x-core::icon name="ti ti-circle-off" />
                                <div>
                                    <span class="text-uppercase">{{ trans('plugins/ecommerce::order.order_was_canceled') }}</span>

                                    @if($order->cancellation_reason)
                                        <div class="text-muted small">
                                            {{ trans('plugins/ecommerce::order.cancellation_reason', ['reason' => $order->cancellation_reason_message]) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED && !$order->shipment->id)
                        <div class="p-3 d-flex justify-content-between align-items-center">
                            <div class="text-uppercase">
                                <x-core::icon name="ti ti-check" class="text-success" />
                                <span>{{ trans('plugins/ecommerce::order.all_products_are_not_delivered') }}</span>
                            </div>
                        </div>
                    @else
                        @if (! EcommerceHelper::isDisabledPhysicalProduct() && $order->shipment->id)
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="text-uppercase">
                                    <x-core::icon name="ti ti-check" class="text-success" />
                                    <span>{{ trans('plugins/ecommerce::order.delivery') }}</span>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if(! EcommerceHelper::isDisabledPhysicalProduct())
                        @if (!$order->shipment->id)
                            <div class="shipment-create-wrap"  style="display: none;"></div>
                        @else
                            @include(MarketplaceHelper::viewPath('vendor-dashboard.orders.shipment-detail'), [
                                'shipment' => $order->shipment,
                            ])
                        @endif
                    @endif
                </x-core::card>

                <x-core::card>
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::order.history') }}
                        </x-core::card.title>
                    </x-core::card.header>

                    <x-core::card.body>
                        <ul class="steps steps-vertical" id="order-history-wrapper">
                            @foreach ($order->histories()->orderByDesc('id')->get() as $history)
                                <li @class(['step-item', 'user-action' => $history->user_id])>
                                    <div class="h4 m-0">
                                        @if (in_array($history->action, ['confirm_payment', 'refund']))
                                            <a
                                                class="show-timeline-dropdown text-primary"
                                                data-target="#history-line-{{ $history->id }}"
                                                href="javascript:void(0)"
                                            >
                                                {{ OrderHelper::processHistoryVariables($history) }}
                                            </a>
                                        @else
                                            {{ OrderHelper::processHistoryVariables($history) }}
                                        @endif
                                    </div>
                                    <div class="text-secondary">{{ BaseHelper::formatDateTime($history->created_at) }}</div>

                                    @if ($history->action == 'refund' && Arr::get($history->extras, 'amount', 0) > 0)
                                        <div
                                            class="timeline-dropdown bg-body mt-2 rounded-2"
                                            style="display: none"
                                            id="history-line-{{ $history->id }}"
                                        >
                                            <x-core::table :striped="false" :hover="false" class="w-100">
                                                <x-core::table.body>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.order_number') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            <a
                                                                href="{{ route('marketplace.vendor.orders.edit', $order->id) }}"
                                                                title="{{ $order->code }}"
                                                            >
                                                                {{ $order->code }}
                                                            </a>
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.description') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            @if(is_plugin_active('payment'))
                                                                {{ $history->description . ' ' . trans('plugins/ecommerce::order.from') . ' ' . $order->payment->payment_channel->label() }}
                                                            @else
                                                                {{ $history->description }}
                                                            @endif
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.amount') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ format_price(Arr::get($history->extras, 'amount', 0)) }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.status') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.successfully') }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.transaction_type') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.refund') }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    @if (trim($history->user->name))
                                                        <x-core::table.body.row>
                                                            <x-core::table.body.cell>
                                                                {{ trans('plugins/ecommerce::order.staff') }}
                                                            </x-core::table.body.cell>
                                                            <x-core::table.body.cell>
                                                                {{ $history->user->name ?: trans('plugins/ecommerce::order.n_a') }}
                                                            </x-core::table.body.cell>
                                                        </x-core::table.body.row>
                                                    @endif
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.refund_date') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ BaseHelper::formatDateTime($history->created_at) }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                </x-core::table.body>
                                            </x-core::table>
                                        </div>
                                    @endif
                                    @if (is_plugin_active('payment') && $history->action == 'confirm_payment' && $order->payment)
                                        <div
                                            class="timeline-dropdown bg-body mt-2 rounded-2"
                                            style="display: none"
                                            id="history-line-{{ $history->id }}"
                                        >
                                            <x-core::table :striped="false" :hover="false" class="w-100">
                                                <tbody>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.order_number') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        <a
                                                            href="{{ route('marketplace.vendor.orders.edit', $order->id) }}"
                                                            title="{{ $order->code }}"
                                                        >
                                                            {{ $order->code }}
                                                        </a>
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.description') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {!! trans('plugins/ecommerce::order.mark_payment_as_confirmed', [
                                                            'method' => $order->payment->payment_channel->label(),
                                                        ]) !!}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.transaction_amount') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ format_price($order->payment->amount) }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.payment_gateway') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ $order->payment->payment_channel->label() }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.status') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.successfully') }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.transaction_type') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.confirm') }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                @if (trim($history->user->name))
                                                    <x-core::table.body.row>
                                                        <x-core::table.body.cell>
                                                            {{ trans('plugins/ecommerce::order.staff') }}
                                                        </x-core::table.body.cell>
                                                        <x-core::table.body.cell>
                                                            {{ $history->user->name ?: trans('plugins/ecommerce::order.n_a') }}
                                                        </x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                @endif
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.payment_date') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        {{ BaseHelper::formatDateTime($history->created_at) }}
                                                    </x-core::table.body.cell>
                                                </x-core::table.body.row>
                                                </tbody>
                                            </x-core::table>
                                        </div>
                                    @endif
                                    @if ($history->action == 'send_order_confirmation_email')
                                        <x-core::button
                                            type="button"
                                            color="primary"
                                            :outlined="true"
                                            class="btn-trigger-resend-order-confirmation-modal position-absolute top-0 end-0 d-print-none"
                                            :data-action="route('marketplace.vendor.orders.send-order-confirmation-email', $history->order_id)"
                                        >
                                            {{ trans('plugins/ecommerce::order.resend') }}
                                        </x-core::button>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </x-core::card.body>
                </x-core::card>

                @include('plugins/ecommerce::orders.partials.digital-product-downloads-info', compact('order'))
            </div>

            <div class="col-md-4">
                {!! apply_filters('ecommerce_order_detail_sidebar_top', null, $order) !!}

                <x-core::card>
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::order.customer_label') }}
                        </x-core::card.title>
                    </x-core::card.header>
                    <x-core::card.body class="p-0">
                        <div class="p-3">
                            <div class="mb-3">
                                <span class="avatar avatar-lg avatar-rounded" style="background-image: url('{{ $order->user->id ? $order->user->avatar_url : $order->address->avatar_url }}')"></span>
                            </div>

                            @php
                                $userInfo = $order->address->id ? $order->address : $order->user;
                            @endphp

                            <p class="mb-1 fw-semibold">{{ $userInfo->name }}</p>

                            @if ($userInfo->id)
                                <p class="mb-1">
                                    <x-core::icon name="ti ti-inbox" />
                                    {{ $order->user->completedOrders()->count() }}
                                    {{ trans('plugins/ecommerce::order.orders') }}
                                </p>
                            @endif

                            @if ($userInfo->email)
                                <p class="mb-1">
                                    <a href="mailto:{{ $userInfo->email }}">
                                        {{ $userInfo->email }}
                                    </a>
                                </p>
                            @endif

                            @if ($order->user->id)
                                <p class="mb-1">{{ trans('plugins/ecommerce::order.have_an_account_already') }}</p>
                            @else
                                <p class="mb-1">{{ trans('plugins/ecommerce::order.dont_have_an_account_yet') }}</p>
                            @endif
                        </div>

                        @if (!EcommerceHelper::countDigitalProducts($order->products) && ! EcommerceHelper::isDisabledPhysicalProduct() && ! EcommerceHelper::isDisabledPhysicalProduct())
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4>{{ trans('plugins/ecommerce::order.shipping_info') }}</h4>

                                    @if ($order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                        <a
                                            class="btn-trigger-update-shipping-address btn-action text-decoration-none"
                                            href="#"
                                            data-placement="top"
                                            data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ trans('plugins/ecommerce::order.update_address') }}"
                                        >
                                            <x-core::icon name="ti ti-pencil" />
                                        </a>
                                    @endif
                                </div>

                                <dl class="shipping-address-info mb-0">
                                    @include(
                                        'plugins/ecommerce::orders.shipping-address.detail',
                                        ['address' => $order->shippingAddress]
                                    )
                                </dl>
                            </div>
                        @endif

                        @if (
                            EcommerceHelper::isBillingAddressEnabled()
                            && $order->billingAddress->id
                            && $order->billingAddress->id != $order->shippingAddress->id
                        )
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4>{{ trans('plugins/ecommerce::order.billing_address') }}</h4>
                                </div>
                            </div>

                            <dl class="shipping-address-info mb-0">
                                @include('plugins/ecommerce::orders.shipping-address.detail', [
                                    'address' => $order->billingAddress,
                                ])
                            </dl>
                        @endif

                        @if ($order->referral()->count())
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <h4>{{ trans('plugins/ecommerce::order.referral') }}</h4>

                                <dl class="mb-0">
                                    @foreach (['ip', 'landing_domain', 'landing_page', 'landing_params', 'referral', 'gclid', 'fclid', 'utm_source', 'utm_campaign', 'utm_medium', 'utm_term', 'utm_content', 'referrer_url', 'referrer_domain'] as $field)
                                        @if ($order->referral->{$field})
                                            <dt>{{ trans('plugins/ecommerce::order.referral_data.' . $field) }}</dt>
                                            <dd>{{ $order->referral->{$field} }}</dd>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        @endif
                    </x-core::card.body>

                    @if ($order->canBeCanceledByAdmin())
                        <x-core::card.footer>
                            <x-core::button
                                type="button"
                                class="btn-trigger-cancel-order"
                                :data-target="route('marketplace.vendor.orders.cancel', $order->id)"
                            >
                                {{ trans('plugins/ecommerce::order.cancel') }}
                            </x-core::button>
                        </x-core::card.footer>
                    @endif
                </x-core::card>

                {!! apply_filters('ecommerce_order_detail_sidebar_bottom', null, $order) !!}
            </div>
        </div>

        {!! apply_filters('ecommerce_order_detail_bottom', null, $order) !!}
    </div>
@endsection

@pushif ($order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED, 'footer')
    @include('plugins/ecommerce::orders.edit.modal', [
        'updateShippingAddressRoute' => 'marketplace.vendor.orders.update-shipping-address',
    ])

    @if (! EcommerceHelper::isDisabledPhysicalProduct() && $order->shipment && $order->shipment->id)
        <x-core::modal
            id="update-shipping-status-modal"
            :title="trans('plugins/ecommerce::shipping.update_shipping_status')"
            button-id="confirm-update-shipping-status-button"
            :button-label="trans('plugins/ecommerce::order.update')"
        >
            @include(MarketplaceHelper::viewPath('vendor-dashboard.orders.shipping-status-modal'), [
                'shipment' => $order->shipment,
                'url' => route('marketplace.vendor.orders.update-shipping-status', $order->shipment->id),
            ])
        </x-core::modal>
    @endif
@endpushif
