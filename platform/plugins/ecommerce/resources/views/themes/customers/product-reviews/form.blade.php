@php
    $version = EcommerceHelper::getAssetVersion();
    Theme::asset()->add('front-review-css', 'vendor/core/plugins/ecommerce/css/front-review.css', version: $version);
    Theme::asset()->add('ecommerce-review-css', 'vendor/core/plugins/ecommerce/css/review.css', version: $version);
    Theme::asset()->container('footer')->add('ecommerce-review-js', 'vendor/core/plugins/ecommerce/js/review.js', ['jquery'], version: $version);
@endphp

<x-core::form :url="route('public.reviews.create')" method="post" class="ecommerce-form-review-product product-review-container" :files="true">
    <input name="product_id" type="hidden" value="{{ $product ? $product->id : '' }}">

    <div class="mb-4">
        <h2 class="fs-5 fw-semibold ecommerce-product-name mb-3" id="product-review-modal-label">
            {!! BaseHelper::clean($product ? $product->name : '') !!}
        </h2>

        <div class="d-flex align-items-center mb-3">
            <label class="form-label mb-0 required" for="rating">{{ __('Your rating:') }}</label>
            <div class="form-rating-stars ms-2">
                @for ($i = 5; $i >= 1; $i--)
                    <input
                        class="btn-check"
                        id="rating-star-{{ $i }}"
                        name="star"
                        type="radio"
                        value="{{ $i }}"
                        @checked($i === 5)
                    >
                    <label for="rating-star-{{ $i }}" title="{{ $i }} stars">
                        <x-core::icon name="ti ti-star-filled" />
                    </label>
                @endfor
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <x-core::form.label for="txt-comment" class="required">
                {{ __('Review') }}
            </x-core::form.label>
            <x-core::form.textarea
                id="txt-comment"
                name="comment"
                :required="true"
                rows="5"
                :placeholder="__('Write your review')"
                aria-describedby="review-help"
            />
            <x-core::form.helper-text id="review-help">
                {{ __('Share your experience with this product to help other customers.') }}
            </x-core::form.helper-text>
        </div>
        <div class="col-12 mb-3">
            <x-core::form.label class="mb-2">
                {{ __('Photos') }} <span class="text-muted">({{ __('Optional') }})</span>
            </x-core::form.label>

            <x-core::custom-template id="ecommerce-review-image-template">
                <span class="ecommerce-image-viewer__item" data-id="__id__">
                    <img src="{{ RvMedia::getDefaultImage() }}" alt="{{ __('Preview') }}" class="img-responsive d-block">
                    <span class="ecommerce-image-viewer__icon-remove image-viewer__icon-remove" title="{{ __('Remove image') }}">
                        <x-core::icon name="ti ti-x" class="ecommerce-icon" />
                    </span>
                </span>
            </x-core::custom-template>

            <div class="ecommerce-image-upload__viewer d-flex">
                <div class="ecommerce-image-viewer__list position-relative">
                    <div class="ecommerce-image-upload__uploader-container">
                        <div class="d-table">
                            <div class="ecommerce-image-upload__uploader border border-2 border-dashed rounded p-4 text-center">
                                <x-core::icon name="ti ti-photo-plus" class="ecommerce-icon ecommerce-image-upload__icon mb-2 text-muted" style="font-size: 2rem;" />
                                <div class="ecommerce-image-upload__text fw-medium mb-1">{{ __('Upload photos') }}</div>
                                <input
                                    class="ecommerce-image-upload__file-input"
                                    name="images[]"
                                    data-max-files="{{ EcommerceHelper::reviewMaxFileNumber() }}"
                                    data-max-size="{{ EcommerceHelper::reviewMaxFileSize(true) }}"
                                    data-max-size-message="{{ trans('validation.max.file', ['attribute' => '__attribute__', 'max' => '__max__']) }}"
                                    type="file"
                                    accept="image/png,image/jpeg,image/jpg"
                                    multiple="multiple"
                                    aria-label="{{ __('Upload review photos') }}"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-core::form.helper-text class="mt-2">
                {{ __('You can upload up to :total photos, each photo maximum size is :max kilobytes', [
                    'total' => EcommerceHelper::reviewMaxFileNumber(),
                    'max' => EcommerceHelper::reviewMaxFileSize(true),
                ]) }}
            </x-core::form.helper-text>
        </div>
        <div class="col-12 mb-3">
            <div class="alert alert-warning alert-message d-none" role="alert"></div>
        </div>

        <div class="col-12">
            <button
                class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 mb-3"
                type="submit"
            >
                <x-core::icon name="ti ti-send" />
                <span>{{ __('Submit Review') }}</span>
            </button>

            <div class="text-muted small mt-2 mt-sm-0">
                <x-core::icon name="ti ti-info-circle" class="me-1" />
                {{ __('Your email address will not be published. Required fields are marked *') }}
            </div>
        </div>
    </div>
</x-core::form>
