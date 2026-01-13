@php AdminBar::setIsDisplay(false); @endphp

<div class="container-fluid p-0 coming-soon-page">
    <div class="row g-0 h-100">
        <div class="col-md-6">
            <div class="d-flex justify-content-center h-100 align-items-center">
                <div class="px-3 px-xl-5 pb-5 mb-5">
                    <h1 class="mb-4">{{ $shortcode->title }}</h1>
                    <p class="mb-4">{{ $shortcode->subtitle }}</p>
                    @if ($shortcode->time)
                        <div class="countdown-wrapper mt-3">
                            <div
                                class="expire-countdown"
                                data-expire="{{ now()->diffInSeconds($shortcode->time) }}"
                            >

                            </div>
                        </div>
                    @endif
                    @if ($socialLinks = Theme::getSocialLinks())
                        <div class="footer-socials mt-5">
                            <p class="me-3 mb-0">{!! BaseHelper::clean($shortcode->social_title) !!}:</p>
                            <div class="footer-socials-container mt-3">
                                <ul class="ps-0 mb-0">
                                    @foreach($socialLinks as $socialLink)
                                        @continue(! $socialLink->getUrl() || ! $socialLink->getIconHtml())

                                        <li class="d-inline-block @if (!$loop->first) ps-1 @endif pe-2">
                                            <a {!! $socialLink->getAttributes() !!}>{{ $socialLink->getIconHtml() }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if ($shortcode->image)
            <div class="col-md-6 d-none d-md-block"><img
                    class="lazyload img-cover h-100 w-100"
                    data-src="{{ RvMedia::getImageUrl($shortcode->image) }}"
                    alt="coming-soon"
                ></div>
        @endif
    </div>
</div>
