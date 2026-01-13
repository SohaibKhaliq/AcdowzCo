@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div id="main-order-content">
        @include('plugins/ecommerce::orders.partials.canceled-alert', compact('order'))

        {!! apply_filters('ecommerce_order_detail_top', null, $order) !!}

        <div class="row row-cards">
            <div class="col-md-9">
                <x-core::card class="mb-3">
                    @include('plugins/ecommerce::orders.edit.order-header')

                    @include('plugins/ecommerce::orders.edit.order-products', [
                        'editProductRoute' => Auth::user()->hasPermission('products.edit') ? 'products.edit' : null,
                        'isInAdmin' => true,
                    ])

                    <x-core::card.body>
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                @include('plugins/ecommerce::orders.edit.order-info', [
                                    'isInAdmin' => true,
                                    'proofDownloadUrl' => route('orders.download-proof', $order->id),
                                ])

                                <div class="btn-list justify-content-end my-3">
                                    @if ($order->isInvoiceAvailable())
                                        <x-core::button
                                            tag="a"
                                            href="{{ route('orders.generate-invoice', $order->id) }}?type=print"
                                            target="_blank"
                                            icon="ti ti-printer"
                                        >
                                            {{ trans('plugins/ecommerce::order.print_invoice') }}
                                        </x-core::button>
                                        <x-core::button
                                            tag="a"
                                            :href="route('orders.generate-invoice', $order->id)"
                                            target="_blank"
                                            icon="ti ti-download"
                                        >
                                            {{ trans('plugins/ecommerce::order.download_invoice') }}
                                        </x-core::button>
                                    @elseif ($order->status != \Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                        <x-core::button
                                            tag="a"
                                            :href="route('orders.invoice.generate', $order->id)"
                                            target="_blank"
                                            icon="ti ti-file-type-pdf"
                                        >
                                            {{ trans('plugins/ecommerce::order.generate_invoice') }}
                                        </x-core::button>
                                    @endif
                                </div>

                                @include('plugins/ecommerce::orders.edit.form-edit', ['route' => 'orders.edit'])
                            </div>
                        </div>
                    </x-core::card.body>

                    <div class="list-group list-group-flush">
                        @if ($order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED || $order->is_confirmed)
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
                                    <form action="{{ route('orders.confirm') }}">
                                        <input name="order_id" type="hidden" value="{{ $order->id }}">
                                        <x-core::button type="button" color="info" class="btn-confirm-order">
                                            {{ trans('plugins/ecommerce::order.confirm') }}
                                        </x-core::button>
                                    </form>
                                @endif
                            </div>
                        @endif
                        @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED || is_plugin_active('payment') && $order->payment->id)
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
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
                                @endif

                                @if (is_plugin_active('payment') && $order->payment->id && $order->payment->status != \Botble\Payment\Enums\PaymentStatusEnum::REFUNDED)
                                    <div class="text-uppercase">
                                        @if (!$order->payment->status || $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                            <x-core::icon name="ti ti-credit-card" />
                                        @elseif (
                                            $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED
                                            || $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::PENDING
                                        )
                                            <x-core::icon name="ti ti-check" class="text-success" />
                                        @endif

                                        @if (!$order->payment->status || $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                            {{ trans('plugins/ecommerce::order.pending_payment') }}
                                        @elseif ($order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED)
                                            {{ trans('plugins/ecommerce::order.payment_was_accepted', ['money' => format_price($order->payment->amount - $order->payment->refunded_amount)]) }}
                                        @elseif ($order->payment->amount - $order->payment->refunded_amount == 0)
                                            {{ trans('plugins/ecommerce::order.payment_was_refunded') }}
                                        @endif
                                    </div>

                                    <div class="btn-list">
                                        @if (!$order->payment->status || $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::PENDING)
                                            <x-core::button
                                                type="button"
                                                color="info"
                                                class="btn-trigger-confirm-payment"
                                                :data-target="route('orders.confirm-payment', $order->id)"
                                            >
                                                {{ trans('plugins/ecommerce::order.confirm_payment') }}
                                            </x-core::button>
                                        @endif
                                        @if (
                                            $order->payment->status == Botble\Payment\Enums\PaymentStatusEnum::COMPLETED
                                            && (
                                                $order->payment->amount - $order->payment->refunded_amount > 0
                                                || $order->products->sum('qty') - $order->products->sum('restock_quantity') > 0
                                            )
                                        )
                                            <x-core::button type="button" class="btn-trigger-refund">
                                                {{ trans('plugins/ecommerce::order.refund') }}
                                            </x-core::button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if (EcommerceHelper::countDigitalProducts($order->products) != $order->products->count() && ! EcommerceHelper::isDisabledPhysicalProduct())
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                @if ($order->status == Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED && !$order->shipment->id)
                                    <div class="text-uppercase">
                                        <x-core::icon name="ti ti-check" class="text-success" />
                                        <span>{{ trans('plugins/ecommerce::order.all_products_are_not_delivered') }}</span>
                                    </div>
                                @else
                                    @if (! EcommerceHelper::isDisabledPhysicalProduct() && $order->shipment->id)
                                        <div class="text-uppercase">
                                            <x-core::icon name="ti ti-check" class="text-success" />
                                            <span>{{ trans('plugins/ecommerce::order.delivery') }}</span>
                                        </div>
                                    @else
                                        <div class="text-uppercase">
                                            <x-core::icon name="ti ti-truck" />
                                            <span>{{ trans('plugins/ecommerce::order.delivery') }}</span>
                                        </div>

                                        <x-core::button
                                            type="button"
                                            class="btn-trigger-shipment"
                                            color="info"
                                            :data-target="route('orders.get-shipment-form', $order->id)"
                                        >
                                            {{ trans('plugins/ecommerce::order.delivery') }}
                                        </x-core::button>
                                    @endif
                                @endif
                            </div>

                            @if(! EcommerceHelper::isDisabledPhysicalProduct())
                                @if (! $order->shipment->id)
                                    <div class="p-3 shipment-create-wrap" style="display: none;"></div>
                                @else
                                    @include('plugins/ecommerce::orders.shipment-detail', [
                                        'shipment' => $order->shipment,
                                    ])
                                @endif
                            @endif
                        @endif
                    </div>
                </x-core::card>

                @include('plugins/ecommerce::orders.partials.digital-product-downloads-info', compact('order'))

                @if ($order->histories()->exists())
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
                                                {!! OrderHelper::processHistoryVariables($history) !!}
                                            </a>
                                        @else
                                            {!! OrderHelper::processHistoryVariables($history) !!}
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
                                                                href="{{ route('orders.edit', $order->id) }}"
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
                                                            {{ $history->description . ' ' . trans('plugins/ecommerce::order.from') . ' ' . $order->payment->payment_channel->label() }}
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
                                                            {{ trans('plugins/ecommerce::order.refund') }}</x-core::table.body.cell>
                                                    </x-core::table.body.row>
                                                    @if (trim($history->user->name))
                                                        <x-core::table.body.row>
                                                            <x-core::table.body.cell>
                                                                {{ trans('plugins/ecommerce::order.staff') }}
                                                            </x-core::table.body.cell>
                                                            <x-core::table.body.cell>
                                                                {{ $history->user->name}}
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
                                                <x-core::table.body.row>
                                                    <x-core::table.body.cell>
                                                        {{ trans('plugins/ecommerce::order.order_number') }}
                                                    </x-core::table.body.cell>
                                                    <x-core::table.body.cell>
                                                        <a
                                                            href="{{ route('orders.edit', $order->id) }}"
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
                                                    <x-core::table.body.cell>{!! trans('plugins/ecommerce::order.mark_payment_as_confirmed', [
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
                                                            {{ $history->user->name }}
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
                                            </x-core::table>
                                        </div>
                                    @endif
                                    @if ($history->action == 'send_order_confirmation_email')
                                        <x-core::button
                                            type="button"
                                            color="primary"
                                            :outlined="true"
                                            class="btn-trigger-resend-order-confirmation-modal position-absolute top-0 end-0 d-print-none"
                                            :data-action="route('orders.send-order-confirmation-email', $history->order_id)"
                                        >
                                            {{ trans('plugins/ecommerce::order.resend') }}
                                        </x-core::button>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </x-core::card.body>
                </x-core::card>
                @endif
            </div>

            <div class="col-md-3">
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
                                $userInfo = $order->user;
                            @endphp

                            @if ($userInfo->id)
                                <p class="mb-1">
                                    <x-core::icon name="ti ti-inbox" />
                                    {{ $userInfo->completedOrders()->count() }}
                                    {{ trans('plugins/ecommerce::order.orders') }}
                                </p>
                            @endif

                            <p class="mb-1 fw-semibold">{{ $userInfo->name }}</p>

                            @if ($userInfo->email)
                                <p class="mb-1">
                                    <a href="mailto:{{ $userInfo->email }}">
                                        {{ $userInfo->email }}
                                    </a>
                                </p>
                            @endif

                            @if ($userInfo->phone)
                                <p class="mb-1">
                                    <a href="tel:{{ $userInfo->phone }}">
                                        {{ $userInfo->phone }}
                                    </a>
                                </p>
                            @endif

                            @if ($order->user->id)
                                <p class="mb-1">{{ trans('plugins/ecommerce::order.have_an_account_already') }}</p>
                            @else
                                <p class="mb-1">{{ trans('plugins/ecommerce::order.dont_have_an_account_yet') }}</p>
                            @endif
                        </div>

                        @if (
                            ! EcommerceHelper::isDisabledPhysicalProduct()
                            && ($order->shippingAddress->country
                            || $order->shippingAddress->state
                            || $order->shippingAddress->city
                            || $order->shippingAddress->address
                            || $order->shippingAddress->email
                            || $order->shippingAddress->phone)
                        )
                            @if (EcommerceHelper::countDigitalProducts($order->products) != $order->products->count())
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

                                    <dl class="shipping-address-info mb-0">
                                        @include(
                                            'plugins/ecommerce::orders.shipping-address.detail',
                                            ['address' => $order->billingAddress]
                                        )
                                    </dl>
                                </div>
                            @endif
                        @endif

                        @if ($order->taxInformation)
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4>{{ trans('plugins/ecommerce::order.tax_info.name') }}</h4>
                                    @if ($order->status !== Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED)
                                        <div class="flexbox-auto-content-right text-end">
                                            <a
                                                class="btn-trigger-update-tax-information"
                                                href="#"
                                                data-placement="top"
                                                data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ trans('plugins/ecommerce::order.tax_info.update') }}"
                                            >
                                                <x-core::icon name="ti ti-pencil" />
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <dl class="shipping-address-info mb-0">
                                    @include('plugins/ecommerce::orders.tax-information.detail', [
                                        'tax' => $order->taxInformation,
                                    ])
                                </dl>
                            </div>
                        @endif

                        @if ($order->referral->exists())
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

                        @if (is_plugin_active('marketplace') && $order->store->name)
                            <div class="hr my-1"></div>

                            <div class="p-3">
                                <h4 class="mb-2">{{ trans('plugins/marketplace::store.store') }}</h4>
                                <a href="{{ $order->store->url }}" target="_blank">{{ $order->store->name }}</a>
                            </div>
                        @endif
                    </x-core::card.body>

                    <x-core::card.footer>
                        <div class="btn-list">
                            <x-core::button
                                tag="a"
                                :href="route('orders.reorder', ['order_id' => $order->id])"
                            >
                                {{ trans('plugins/ecommerce::order.reorder') }}
                            </x-core::button>
                            @if ($order->canBeCanceledByAdmin())
                                <x-core::button
                                    type="button"
                                    :data-target="route('orders.cancel', $order->id)"
                                    class="btn-trigger-cancel-order"
                                >
                                    {{ trans('plugins/ecommerce::order.cancel') }}
                                </x-core::button>
                            @endif
                        </div>
                    </x-core::card.footer>
                </x-core::card>

                {!! apply_filters('ecommerce_order_detail_sidebar_bottom', null, $order) !!}
            </div>
        </div>

        {!! apply_filters('ecommerce_order_detail_bottom', null, $order) !!}
    </div>
@endsection

@pushif($order->status != Botble\Ecommerce\Enums\OrderStatusEnum::CANCELED, 'footer')
    @include('plugins/ecommerce::orders.edit.modal', [
        'updateShippingAddressRoute' => 'orders.update-shipping-address',
    ])

    <x-core::modal.action
        id="cancel-shipment-modal"
        type="warning"
        :title="trans('plugins/ecommerce::order.cancel_shipping_confirmation')"
        :description="trans('plugins/ecommerce::order.cancel_shipping_confirmation_description')"
        :submit-button-attrs="['id' => 'confirm-cancel-shipment-button']"
        :submit-button-label="trans('plugins/ecommerce::order.confirm')"
    />

    @if ($order->taxInformation)
        <x-core::modal
            id="update-tax-information-modal"
            :title="trans('plugins/ecommerce::order.tax_info.update')"
            button-id="confirm-update-tax-information-button"
            :button-label="trans('plugins/ecommerce::order.update')"
            size="md"
        >
            @include('plugins/ecommerce::orders.tax-information.form', [
                'tax' => $order->taxInformation,
                'orderId' => $order->id,
            ])
        </x-core::modal>
    @endif

    @if (! EcommerceHelper::isDisabledPhysicalProduct() && $order->shipment && $order->shipment->id)
        @include('plugins/ecommerce::shipments.partials.update-status-modal', [
            'shipment' => $order->shipment,
        ])
    @endif
@endpushif

@pushif(is_plugin_active('payment') && $order->payment->id && $order->payment->status != \Botble\Payment\Enums\PaymentStatusEnum::REFUNDED, 'footer')
    <x-core::modal.action
        id="confirm-payment-modal"
        type="info"
        :title="trans('plugins/ecommerce::order.confirm_payment')"
        :description="trans('plugins/ecommerce::order.confirm_payment_confirmation_description', [
            'method' => $order->payment->payment_channel->label(),
        ])"
        :submit-button-attrs="['id' => 'confirm-payment-order-button']"
        :submit-button-label="trans('plugins/ecommerce::order.confirm_payment')"
    />

    <x-core::modal
        id="confirm-refund-modal"
        :title="trans('plugins/ecommerce::order.refund')"
        button-id="confirm-refund-payment-button"
        size="lg"
    >
        <x-slot:button-label>
            {{ trans('plugins/ecommerce::order.confirm_payment') }}
            <span class="refund-amount-text ms-1">{{ format_price($order->payment->amount - $order->payment->refunded_amount) }}</span>
        </x-slot:button-label>
        @include('plugins/ecommerce::orders.refund.modal', [
            'order' => $order,
            'url' => route('orders.refund', $order->id),
        ])
    </x-core::modal>
@endpushif
