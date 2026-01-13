@if ($logo = theme_option('logo_in_the_checkout_page') ?: Theme::getLogo())
    <div class="checkout-logo text-center text-sm-start">
        <a
            href="{{ BaseHelper::getHomepageUrl() }}"
            title="{{ $siteTitle = Theme::getSiteTitle() }}"
        >
            <img
                src="{{ RvMedia::getImageUrl($logo) }}"
                alt="{{ $siteTitle }}"
            />
        </a>
    </div>
    <hr class="border-dark-subtle" />
@endif
