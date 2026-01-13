<section
    class="content-page single-product-content pt-50 pb-50"
    id="product-detail-page"
>
    @include(EcommerceHelper::viewPath('includes.product-detail'))

    <div class="card product-detail-tabs mt-5">
        <ul class="nav nav-pills nav-fill bb-product-content-tabs p-4 pb-0">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" aria-current="page" href="#tab-description">{{ __('Description') }}</a>
            </li>

            @if (EcommerceHelper::isProductSpecificationEnabled() && $product->specificationAttributes->where('pivot.hidden', false)->isNotEmpty())
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" aria-current="page" href="#tab-specification">{{ __('Specification') }}</a>
                </li>
            @endif

            @if (EcommerceHelper::isReviewEnabled())
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-reviews">{{ __('Reviews :number', [
                        'number' => $product->reviews_count > 1 ? sprintf('(%s)', $product->reviews_count) : null
                    ]) }}
                    </a>
                </li>
            @endif
        </ul>

        <div class="bb-product-content-tabs-wrapper tab-content container p-4">
            <div
                class="tab-pane fade in active show"
                id="tab-description"
                role="tabpanel"
                aria-labelledby="nav-description-tab"
                tabindex="0"
            >
                <div class="ck-content">
                    {!! BaseHelper::clean($product->content) !!}
                </div>
            </div>

            @if (EcommerceHelper::isProductSpecificationEnabled() && $product->specificationAttributes->where('pivot.hidden', false)->isNotEmpty())
                <div class="tab-pane fade" id="tab-specification" role="tabpanel" aria-labelledby="nav-specification-tab" tabindex="1">
                    <div class="tp-product-details-additional-info">
                        @include(EcommerceHelper::viewPath('includes.product-specification'))
                    </div>
                </div>
            @endif

            @if (EcommerceHelper::isReviewEnabled())
                <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="nav-review-tab" tabindex="2">
                    <div class="tp-product-details-review-wrapper pt-60" id="product-reviews">
                        @include(EcommerceHelper::viewPath('includes.reviews'))
                    </div>
                </div>
            @endif
        </div>
    </div>

    @php
        $relatedProducts = get_related_products($product);
    @endphp

    @if ($relatedProducts->isNotEmpty())
        <div class="container mt-5">
            <h2>{{ __('Related products') }}</h2>

            <div class="row">
                @include(EcommerceHelper::viewPath('includes.product-items'), ['products' => $relatedProducts])
            </div>
        </div>
    @endif
</section>
