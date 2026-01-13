@foreach ($reviews as $review)
    @continue(! $review->is_approved && auth('customer')->id() != $review->customer_id)

    @php
        $isCurrentCustomerReview = auth('customer')->check() && auth('customer')->id() == $review->customer_id;
    @endphp

    <div @class([
        'row pb-3 mb-3 review-item',
        'border-bottom' => ! $loop->last,
        'opacity-50' => ! $review->is_approved,
        'current-customer-review' => $isCurrentCustomerReview
    ])>
        <div class="col-auto">
            <img class="rounded-circle" src="{{ $review->customer_avatar_url }}" alt="{{ $review->display_name }}" width="60">
        </div>
        <div class="col">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2 review-item__header">
                <div class="fw-medium">
                    {{ $review->display_name }}
                </div>
                @if ($isCurrentCustomerReview)
                    <span class="badge bg-primary">
                        {{ __('Your Review') }}
                    </span>
                @endif
                <time class="text-muted small" datetime="{{ $review->created_at->translatedFormat('Y-m-d\TH:i:sP') }}">
                    {{ $review->created_at->diffForHumans() }}
                </time>
                @if ($review->order_created_at)
                    <div class="small text-muted">{{ __('✅ Purchased :time', ['time' => $review->order_created_at->diffForHumans()]) }}</div>
                @endif
                @if (! $review->is_approved)
                    <div class="small text-warning">{{ __('Waiting for approval') }}</div>
                @endif

                @if ($isCurrentCustomerReview)
                    <div class="review-item__actions">
                        <a
                            href="javascript:void(0)"
                            class="text-danger delete-review-btn p-1"
                            data-review-id="{{ $review->id }}"
                            data-confirm-message="{{ __('Are you sure you want to delete your review?') }}"
                            title="{{ __('Delete your review') }}"
                        >
                            <x-core::icon name="ti ti-trash" />
                        </a>
                    </div>
                @endif
            </div>

            <div class="mb-2 review-item__rating">
                @include(EcommerceHelper::viewPath('includes.rating-star'), ['avg' => $review->star, 'size' => 80])
            </div>

            <div class="review-item__body">
                {{ $review->comment }}
            </div>

            @if ($review->images)
                <div class="review-item__images mt-3">
                    <div class="row g-1 review-images">
                        @foreach ($review->images as $image)
                            <a href="{{ RvMedia::getImageUrl($image) }}" class="col-3 col-md-2 col-xl-1 position-relative">
                                <img src="{{ RvMedia::getImageUrl($image, 'thumb') }}" alt="{{ $review->comment }}" class="img-thumbnail">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($review->reply)
            <div class="review-item__reply mt-4">
                <div class="position-relative row py-3 rounded bg-light">
                    <div class="col-auto">
                        <img class="rounded-circle" src="{{ $review->reply->user->avatar_url }}" alt="{{ $review->reply->user->name }}" width="50">
                    </div>
                    <div class="col">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2 review-item__header">
                            <div class="fw-medium">
                                {{ $review->reply->user->name }}
                            </div>
                            <span class="badge bg-primary">
                                {{ __('Admin') }}
                            </span>
                            <time class="text-muted small" datetime="{{ $review->reply->created_at->translatedFormat('Y-m-d\TH:i:sP') }}">
                                {{ $review->reply->created_at->diffForHumans() }}
                            </time>
                        </div>

                        <div class="review-item__body">
                            {{ $review->reply->message }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endforeach

<div class="tp-pagination">
    {{ $reviews->links() }}
</div>
