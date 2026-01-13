@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row row-cards">
        <div class="col-md-3">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        {{ trans('plugins/ecommerce::customer.customer_information') }}
                    </x-core::card.title>
                </x-core::card.header>

                <x-core::card.body class="p-0">
                    <div class="text-center p-3">
                        <div class="mb-2">
                            <img
                                src="{{ $customer->avatar_url ?: RvMedia::getDefaultImage() }}"
                                alt="{{ $customer->name }}"
                                class="avatar avatar-rounded avatar-xl"
                            />
                        </div>

                        <h3 class="m-0">{{ $customer->name }}</h3>
                        <p class="text-muted">{{ $customer->email }}</p>
                        
                        @if($customer->phone)
                            <p class="text-muted mb-1">
                                <x-core::icon name="ti ti-phone" />
                                {{ $customer->phone }}
                            </p>
                        @endif

                        <div class="mt-2">
                            @if($customer->confirmed_at)
                                <span class="badge bg-green text-green-fg">
                                    <x-core::icon name="ti ti-check" />
                                    {{ trans('plugins/ecommerce::customer.email_verified') }}
                                </span>
                            @else
                                <span class="badge bg-yellow text-yellow-fg">
                                    <x-core::icon name="ti ti-alert-circle" />
                                    {{ trans('plugins/ecommerce::customer.email_not_verified') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="hr my-2"></div>

                    <div class="p-3">
                        <dl class="row">
                            <dt class="col">{{ trans('plugins/ecommerce::customer.status') }}</dt>
                            <dd class="col-auto">
                                {!! BaseHelper::clean($customer->status->toHtml()) !!}
                            </dd>
                        </dl>
                        
                        @if($customer->dob)
                            <dl class="row">
                                <dt class="col">{{ trans('plugins/ecommerce::customer.dob') }}</dt>
                                <dd class="col-auto">{{ $customer->dob->format('Y-m-d') }}</dd>
                            </dl>
                        @endif

                        @if($customer->created_at)
                            <dl class="row">
                                <dt class="col">{{ trans('core/base::tables.created_at') }}</dt>
                                <dd class="col-auto">{{ $customer->created_at->format('Y-m-d H:i') }}</dd>
                            </dl>
                        @endif

                        <dl class="row">
                            <dt class="col">{{ trans('plugins/ecommerce::customer.total_orders') }}</dt>
                            <dd class="col-auto">{{ number_format($totalOrders) }}</dd>
                        </dl>

                        <dl class="row">
                            <dt class="col">{{ trans('plugins/ecommerce::customer.completed_orders') }}</dt>
                            <dd class="col-auto">{{ number_format($completedOrders) }}</dd>
                        </dl>

                        <dl class="row">
                            <dt class="col">{{ trans('plugins/ecommerce::customer.total_products') }}</dt>
                            <dd class="col-auto">{{ number_format($totalProducts) }}</dd>
                        </dl>

                        <dl class="row">
                            <dt class="col">{{ trans('plugins/ecommerce::customer.total_spent') }}</dt>
                            <dd class="col-auto">{{ format_price($totalSpent) }}</dd>
                        </dl>
                    </div>

                    <div class="hr my-2"></div>

                    <div class="p-3">
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary w-100">
                            <x-core::icon name="ti ti-edit" />
                            {{ trans('plugins/ecommerce::customer.edit_action') }}
                        </a>
                    </div>
                </x-core::card.body>
            </x-core::card>

            @if($customer->addresses->count() > 0)
                <x-core::card class="mt-3">
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::customer.addresses') }}
                        </x-core::card.title>
                    </x-core::card.header>

                    <x-core::card.body class="p-0">
                        <div class="list-group list-group-flush">
                            @foreach($customer->addresses as $address)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="text-truncate">
                                                <strong>{{ $address->name }}</strong>
                                                @if($address->is_default)
                                                    <span class="badge bg-blue text-blue-fg ms-1">{{ trans('plugins/ecommerce::customer.default') }}</span>
                                                @endif
                                            </div>
                                            <div class="text-muted text-truncate mt-1">
                                                {{ $address->full_address }}
                                            </div>
                                            @if($address->phone)
                                                <div class="text-muted mt-1">
                                                    <x-core::icon name="ti ti-phone" />
                                                    {{ $address->phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-core::card.body>
                </x-core::card>
            @endif
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <x-core::card>
                        <x-core::card.body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ trans('plugins/ecommerce::customer.total_orders') }}</div>
                            </div>
                            <div class="h1 mb-0">{{ number_format($totalOrders) }}</div>
                        </x-core::card.body>
                    </x-core::card>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <x-core::card>
                        <x-core::card.body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ trans('plugins/ecommerce::customer.completed_orders') }}</div>
                            </div>
                            <div class="h1 mb-0">{{ number_format($completedOrders) }}</div>
                        </x-core::card.body>
                    </x-core::card>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <x-core::card>
                        <x-core::card.body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ trans('plugins/ecommerce::customer.total_products') }}</div>
                            </div>
                            <div class="h1 mb-0">{{ number_format($totalProducts) }}</div>
                        </x-core::card.body>
                    </x-core::card>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <x-core::card>
                        <x-core::card.body>
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ trans('plugins/ecommerce::customer.total_spent') }}</div>
                            </div>
                            <div class="h1 mb-0">{{ format_price($totalSpent) }}</div>
                        </x-core::card.body>
                    </x-core::card>
                </div>
            </div>

            @if($totalOrders > 0)
                <x-core::card>
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::customer.recent_orders') }}
                        </x-core::card.title>
                    </x-core::card.header>

                    <x-core::table>
                        <x-core::table.header>
                            <x-core::table.header.cell>
                                {{ trans('plugins/ecommerce::order.order_id') }}
                            </x-core::table.header.cell>
                            <x-core::table.header.cell>
                                {{ trans('plugins/ecommerce::order.created_at') }}
                            </x-core::table.header.cell>
                            <x-core::table.header.cell>
                                {{ trans('plugins/ecommerce::order.amount') }}
                            </x-core::table.header.cell>
                            <x-core::table.header.cell>
                                {{ trans('plugins/ecommerce::order.payment_method') }}
                            </x-core::table.header.cell>
                            <x-core::table.header.cell>
                                {{ trans('plugins/ecommerce::order.status') }}
                            </x-core::table.header.cell>
                            <x-core::table.header.cell></x-core::table.header.cell>
                        </x-core::table.header>
                        <x-core::table.body>
                            @foreach($customer->orders()->latest()->limit(10)->get() as $order)
                                <x-core::table.body.row>
                                    <x-core::table.body.cell>
                                        <a href="{{ route('orders.edit', $order->id) }}">
                                            {{ $order->code }}
                                        </a>
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '—' }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ format_price($order->amount) }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {{ $order->payment->payment_channel->label() ?? '—' }}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        {!! BaseHelper::clean($order->status->toHtml()) !!}
                                    </x-core::table.body.cell>
                                    <x-core::table.body.cell>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="text-decoration-none">
                                            {{ trans('core/base::tables.view') }}
                                        </a>
                                    </x-core::table.body.cell>
                                </x-core::table.body.row>
                            @endforeach
                        </x-core::table.body>
                    </x-core::table>
                </x-core::card>
            @endif

            @if($customer->wishlist->count() > 0)
                <x-core::card class="mt-3">
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::customer.wishlist') }} ({{ $customer->wishlist->count() }})
                        </x-core::card.title>
                    </x-core::card.header>

                    <x-core::card.body>
                        <div class="row">
                            @foreach($customer->wishlist()->latest()->limit(8)->get() as $wishlistItem)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="text-center">
                                        <a href="{{ route('products.edit', $wishlistItem->product_id) }}" target="_blank">
                                            <img
                                                src="{{ RvMedia::getImageUrl($wishlistItem->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                                alt="{{ $wishlistItem->product->name }}"
                                                class="img-fluid rounded mb-2"
                                                style="max-height: 100px;"
                                            />
                                            <div class="text-truncate">{{ $wishlistItem->product->name }}</div>
                                            <div class="text-primary">{{ format_price($wishlistItem->product->price) }}</div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-core::card.body>
                </x-core::card>
            @endif

            @if($customer->reviews->count() > 0)
                <x-core::card class="mt-3">
                    <x-core::card.header>
                        <x-core::card.title>
                            {{ trans('plugins/ecommerce::customer.recent_reviews') }} ({{ $customer->reviews->count() }})
                        </x-core::card.title>
                    </x-core::card.header>

                    <x-core::card.body>
                        @foreach($customer->reviews()->latest()->limit(5)->get() as $review)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start">
                                    <img
                                        src="{{ RvMedia::getImageUrl($review->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                        alt="{{ $review->product->name }}"
                                        class="me-3 rounded"
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                    />
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <a href="{{ route('products.edit', $review->product_id) }}" target="_blank" class="text-decoration-none">
                                                    <strong>{{ $review->product->name }}</strong>
                                                </a>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->star)
                                                            <x-core::icon name="ti ti-star-filled" />
                                                        @else
                                                            <x-core::icon name="ti ti-star" />
                                                        @endif
                                                    @endfor
                                                    <span class="text-muted ms-1">({{ $review->star }}/5)</span>
                                                </div>
                                            </div>
                                            <div class="text-muted small">
                                                {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="mb-0 mt-2">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </x-core::card.body>
                </x-core::card>
            @endif
        </div>
    </div>
@endsection