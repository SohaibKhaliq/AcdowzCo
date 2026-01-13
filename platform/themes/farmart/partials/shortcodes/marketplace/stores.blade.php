<div class="widget-products-with-category py-5 bg-light">
    <div class="container-xxxl">
        <div class="row">
            <div class="col-12">
                <div class="row align-items-center mb-2 widget-header">
                    <h2 class="col-auto mb-0 py-2">{{ $shortcode->title }}</h2>
                </div>

                @include(Theme::getThemeNamespace('views.marketplace.includes.store-items'))
            </div>
        </div>
    </div>
</div>
