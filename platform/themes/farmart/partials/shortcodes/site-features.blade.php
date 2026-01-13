<div class="py-3">
    <div class="container-xxxl">
        @if($shortcode->title)
            <div class="align-items-center mb-2 widget-header">
                <h2 class="col-auto mb-0 py-2">{{ $shortcode->title }}</h2>
            </div>
        @endif
        <div
            class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 justify-content-center my-4 g-2">
            @foreach(range(1, $shortcode->quantity) as $i)
                <div class="col py-2">
                    <div class="site-info__item d-flex align-items-start">
                        @if($icon = $shortcode->{"icon_$i"})
                            <div class="site-info__image me-3 mt-1">
                                <img
                                    class="lazyload"
                                    data-src="{{ RvMedia::getImageUrl($icon) }}"
                                    alt="{{ $shortcode->{"title_$i"} }}"
                                >
                            </div>
                        @endif
                        <div class="site-info__content">
                            <div class="site-info__title h4 fw-bold">{{ $shortcode->{"title_$i"} }}</div>
                            <div class="site-info__desc">{!! BaseHelper::clean(nl2br($shortcode->{"subtitle_$i"})) !!}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
