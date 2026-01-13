<style>
.site-notice {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 8px;
    z-index: 99999;
    display: none;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}
.site-notice.site-notice--visible {
    display: block;
}
.site-notice.site-notice-full-width .site-notice-body {
    margin: 0 auto;
}
.site-notice.site-notice-minimal {
    padding: 0;
    right: unset;
    border-radius: 5px;
    bottom: 1em;
    flex-direction: column;
    left: 1em;
}
.site-notice.site-notice-minimal .site-notice-body {
    margin: 0 16px 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    max-width: 400px !important;
}
.site-notice.site-notice-minimal .site-notice__inner {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
    padding: 4px;
}
.site-notice.site-notice-minimal .site-notice__message {
    font-size: 13px;
    line-height: 1.5;
    padding: 12px 12px 0;
}
.site-notice.site-notice-minimal .site-notice__actions {
    margin: 0;
    padding: 8px 12px 12px;
    justify-content: flex-end;
    gap: 8px;
}
.site-notice.site-notice-minimal .site-notice__actions button {
    min-width: auto;
    padding: 6px 12px;
    font-size: 12px;
}
.site-notice.site-notice-minimal .site-notice__categories {
    padding: 12px;
    margin-top: 0;
}
.site-notice.site-notice-minimal .site-notice__categories .cookie-category {
    padding: 8px;
    margin-bottom: 8px;
}
.site-notice.site-notice-minimal .site-notice__categories .cookie-category:last-child {
    margin-bottom: 0;
}
.site-notice.site-notice-minimal .site-notice__categories .cookie-category__description {
    font-size: 12px;
}
.site-notice.site-notice-minimal .site-notice__categories .cookie-consent__save {
    padding: 8px 0 0;
    margin-top: 8px;
}
.site-notice.site-notice-minimal .site-notice__categories .cookie-consent__save .cookie-consent__save-button {
    font-size: 12px;
    padding: 6px 12px;
}
.site-notice .site-notice-body {
    padding: 8px 15px;
    border-radius: 4px;
}
.site-notice .site-notice__inner {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.site-notice .site-notice__message {
    margin: 0;
    line-height: 1.4;
    font-size: 14px;
    flex: 1;
    min-width: 200px;
}
.site-notice .site-notice__message a {
    color: inherit;
    text-decoration: underline;
}
.site-notice .site-notice__message a:hover {
    text-decoration: none;
}
.site-notice .site-notice__categories {
    display: none;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}
.site-notice .site-notice__categories .cookie-category {
    margin-bottom: 1rem;
    padding: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}
.site-notice .site-notice__categories .cookie-category__label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
}
.site-notice .site-notice__categories .cookie-category__label input[type="checkbox"] {
    margin: 0;
    padding: 0;
    border: none;
    border-radius: 0;
    box-shadow: none;
    font-size: initial;
    height: initial;
    width: auto;
}
.site-notice .site-notice__categories .cookie-category__label input[type="checkbox"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.site-notice .site-notice__categories .cookie-category__name {
    font-weight: bold;
}
.site-notice .site-notice__categories .cookie-category__description {
    margin: 0;
    font-size: 0.9em;
    opacity: 0.8;
}
.site-notice .site-notice__categories .cookie-consent__save {
    margin-top: 1rem;
    padding: .75rem;
}
[dir="rtl"] .site-notice .site-notice__categories .cookie-consent__save {
    text-align: left;
}
.site-notice .site-notice__categories .cookie-consent__save .cookie-consent__save-button {
    padding: 6px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 13px;
    min-width: 100px;
    text-align: center;
    font-weight: bold;
}
.site-notice .site-notice__categories .cookie-consent__save .cookie-consent__save-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.site-notice .site-notice__categories .cookie-consent__save .cookie-consent__save-button:active {
    transform: translateY(0);
}
.site-notice .site-notice__actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: flex-end;
    margin-left: auto;
}
[dir="rtl"] .site-notice .site-notice__actions {
    margin-left: 0;
    margin-right: auto;
}
.site-notice .site-notice__actions button {
    padding: 6px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 13px;
    min-width: 100px;
    text-align: center;
}
.site-notice .site-notice__actions button:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.site-notice .site-notice__actions button:active {
    transform: translateY(0);
}
.site-notice .cookie-consent__actions .site-notice__reject {
    font-weight: 500;
    opacity: 0.95;
}
.site-notice .cookie-consent__actions .site-notice__reject:hover {
    opacity: 1;
}
.site-notice .cookie-consent__actions .site-notice__customize {
    font-weight: 500;
    opacity: 0.95;
}
.site-notice .cookie-consent__actions .site-notice__customize:hover {
    opacity: 1;
}
.site-notice .cookie-consent__actions .site-notice__customize.active {
    opacity: 0.8;
    transform: translateY(0);
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}
.site-notice .cookie-consent__actions .site-notice__agree {
    font-weight: bold;
    position: relative;
}
.site-notice .cookie-consent__actions .site-notice__agree:before {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.1);
    z-index: -1;
}
@media (max-width: 767px) {
    .site-notice .site-notice__inner {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    [dir="rtl"] .site-notice .site-notice__inner {
        flex-direction: column;
    }
    .site-notice .site-notice__actions {
        justify-content: center;
        margin-left: 0;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    [dir="rtl"] .site-notice .site-notice__actions {
        margin-right: 0;
    }
    .site-notice .site-notice__actions button {
        flex: none;
        min-width: 70px;
        max-width: 100px;
        padding: 6px 10px;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        border-radius: 3px;
    }
}
</style>

<div
    class="js-site-notice site-notice site-notice-{{ theme_option('cookie_consent_style', 'full-width') }}"
    style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }}; color: {{ theme_option('cookie_consent_text_color', '#fff') }};"
    dir="{{ BaseHelper::siteLanguageDirection() }}"
    data-nosnippet
>
    <div
        class="site-notice-body"
        style="max-width: {{ theme_option('cookie_consent_max_width', 1170) }}px;"
    >
        <div class="site-notice__inner">
            <div class="site-notice__message">
                {!! BaseHelper::clean(theme_option('cookie_consent_message', trans('plugins/cookie-consent::cookie-consent.message'))) !!}
                @if (($learnMoreUrl = theme_option('cookie_consent_learn_more_url')) && ($learnMoreText = theme_option('cookie_consent_learn_more_text')))
                    <a href="{{ Str::startsWith($learnMoreUrl, ['http://', 'https://']) ? $learnMoreUrl : BaseHelper::getHomepageUrl() . '/' . $learnMoreUrl }}">{{ $learnMoreText }}</a>
                @endif
            </div>

            <div class="site-notice__actions">
                @if (theme_option('cookie_consent_show_reject_button', 'no') == 'yes')
                    <button
                        class="js-site-notice-reject site-notice__reject"
                        style="background-color: {{ theme_option('cookie_consent_text_color', '#fff') }}; color: {{ theme_option('cookie_consent_background_color', '#000') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                    >
                        {{ trans('plugins/cookie-consent::cookie-consent.reject_text') }}
                    </button>
                @endif
                @if (! empty($cookieConsentConfig['cookie_categories']) && theme_option('cookie_consent_show_customize_button', 'no') == 'yes')
                    <button
                        class="js-site-notice-customize site-notice__customize"
                        style="background-color: {{ theme_option('cookie_consent_text_color', '#fff') }}; color: {{ theme_option('cookie_consent_background_color', '#000') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                    >
                        {{ trans('plugins/cookie-consent::cookie-consent.customize_text') }}
                    </button>
                @endif
                <button
                    class="js-site-notice-agree site-notice__agree"
                    style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }}; color: {{ theme_option('cookie_consent_text_color', '#fff') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                >
                    {{ theme_option('cookie_consent_button_text', trans('plugins/cookie-consent::cookie-consent.button_text')) }}
                </button>
            </div>
        </div>

        @if (! empty($cookieConsentConfig['cookie_categories']))
            <div class="site-notice__categories">
                @foreach ($cookieConsentConfig['cookie_categories'] as $key => $category)
                    <div class="cookie-category">
                        <label class="cookie-category__label">
                            <input type="checkbox"
                                name="cookie_category[]"
                                value="{{ $key }}"
                                class="js-cookie-category"
                                @if ($category['required']) checked disabled @endif
                            >
                            <span class="cookie-category__name">{{ trans('plugins/cookie-consent::cookie-consent.cookie_categories.' . $key . '.name') }}</span>
                        </label>
                        <p class="cookie-category__description">{{ trans('plugins/cookie-consent::cookie-consent.cookie_categories.' . $key . '.description') }}</p>
                    </div>
                @endforeach

                <div class="site-notice__save">
                    <button
                        class="js-site-notice-save site-notice__save-button"
                        style="background-color: {{ theme_option('cookie_consent_background_color', '#000') }}; color: {{ theme_option('cookie_consent_text_color', '#fff') }}; border: 1px solid {{ theme_option('cookie_consent_text_color', '#fff') }};"
                    >
                        {{ trans('plugins/cookie-consent::cookie-consent.save_text') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<div data-site-cookie-name="{{ $cookieConsentConfig['cookie_name'] ?? 'cookie_for_consent' }}"></div>
<div data-site-cookie-lifetime="{{ $cookieConsentConfig['cookie_lifetime'] ?? 36000 }}"></div>
<div data-site-cookie-domain="{{ config('session.domain') ?? request()->getHost() }}"></div>
<div data-site-session-secure="{{ config('session.secure') ? ';secure' : null }}"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function checkCookie(name) {
            return document.cookie.split(';').some((item) => item.trim().startsWith(name + '='));
        }

        setTimeout(function() {
            const cookieName = document.querySelector('div[data-site-cookie-name]').getAttribute('data-site-cookie-name') || 'cookie_for_consent';
            if (!checkCookie(cookieName)) {
                const siteNotice = document.querySelector('.js-site-notice');
                if (siteNotice) {
                    siteNotice.classList.add('site-notice--visible');
                }
            }
        }, 1000);
    });

    window.addEventListener('load', function () {
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'default', {
                'ad_storage': 'denied',
                'analytics_storage': 'denied'
            });

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('js-site-notice-agree')) {
                    const categories = document.querySelectorAll('.js-cookie-category:checked');
                    const consents = {
                        'ad_storage': 'denied',
                        'analytics_storage': 'denied'
                    };

                    categories.forEach(function(category) {
                        if (category.value === 'marketing') {
                            consents.ad_storage = 'granted';
                        }
                        if (category.value === 'analytics') {
                            consents.analytics_storage = 'granted';
                        }
                    });

                    gtag('consent', 'update', consents);
                }
            });
        }

        window.botbleCookieConsent = (function () {
            const COOKIE_NAME = document.querySelector('div[data-site-cookie-name]').getAttribute('data-site-cookie-name') || 'cookie_for_consent';
            const COOKIE_DOMAIN = document.querySelector('div[data-site-cookie-domain]').getAttribute('data-site-cookie-domain') || window.location.hostname;
            const COOKIE_LIFETIME = parseInt(document.querySelector('div[data-site-cookie-lifetime]').getAttribute('data-site-cookie-lifetime') || '36000', 10);
            const SESSION_SECURE = document.querySelector('div[data-site-session-secure]').getAttribute('data-site-session-secure') || '';

            const cookieDialog = document.querySelector('.js-site-notice');
            const cookieCategories = document.querySelector('.site-notice__categories');
            const customizeButton = document.querySelector('.js-site-notice-customize');

            if (cookieDialog) {
                if (cookieCategories) {
                    cookieCategories.style.display = 'none';
                }

                if (!cookieExists(COOKIE_NAME)) {
                    setTimeout(function() {
                        cookieDialog.classList.add('site-notice--visible');
                    }, 800);
                }
            }

            function consentWithCookies() {
                const categories = {};
                document.querySelectorAll('.js-cookie-category:checked').forEach(function(checkbox) {
                    categories[checkbox.value] = true;
                });
                setCookie(COOKIE_NAME, JSON.stringify(categories), COOKIE_LIFETIME);
                hideCookieDialog();
            }

            function savePreferences() {
                consentWithCookies();

                if (cookieCategories) {
                    const slideUpAnimation = cookieCategories.animate(
                        [
                            { opacity: 1, height: cookieCategories.offsetHeight + 'px' },
                            { opacity: 0, height: 0 }
                        ],
                        { duration: 300, easing: 'ease-out' }
                    );

                    slideUpAnimation.onfinish = function() {
                        cookieCategories.style.display = 'none';
                    };
                }

                if (customizeButton) {
                    customizeButton.classList.remove('active');
                }
            }

            function rejectAllCookies() {
                if (cookieExists(COOKIE_NAME)) {
                    const secure = window.location.protocol === 'https:' ? '; Secure' : '';
                    document.cookie = COOKIE_NAME +
                        '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; domain=' +
                        COOKIE_DOMAIN +
                        '; path=/; SameSite=Lax' +
                        secure +
                        SESSION_SECURE;
                }

                if (typeof gtag !== 'undefined') {
                    gtag('consent', 'update', {
                        'ad_storage': 'denied',
                        'analytics_storage': 'denied'
                    });
                }

                hideCookieDialog();
            }

            function cookieExists(name) {
                const cookie = getCookie(name);
                return cookie !== null && cookie !== undefined;
            }

            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) {
                    return parts.pop().split(';').shift();
                }
                return null;
            }

            function hideCookieDialog() {
                if (cookieDialog) {
                    cookieDialog.classList.remove('site-notice--visible');
                    cookieDialog.style.display = 'none';
                }
            }

            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + expirationInDays * 24 * 60 * 60 * 1000);
                const secure = window.location.protocol === 'https:' ? ';Secure' : '';
                document.cookie =
                    name +
                    '=' +
                    value +
                    ';expires=' +
                    date.toUTCString() +
                    ';domain=' +
                    COOKIE_DOMAIN +
                    ';path=/' +
                    ';SameSite=Lax' +
                    secure +
                    SESSION_SECURE;
            }

            function toggleCustomizeView() {
                if (!cookieCategories) return;

                if (cookieCategories.style.display === 'none') {
                    cookieCategories.style.height = '0';
                    cookieCategories.style.opacity = '0';
                    cookieCategories.style.display = 'block';

                    const height = cookieCategories.scrollHeight;
                    const slideDownAnimation = cookieCategories.animate(
                        [
                            { opacity: 0, height: 0 },
                            { opacity: 1, height: height + 'px' }
                        ],
                        { duration: 300, easing: 'ease-in' }
                    );

                    slideDownAnimation.onfinish = function() {
                        cookieCategories.style.height = 'auto';
                        cookieCategories.style.opacity = '1';
                    };

                    if (customizeButton) {
                        customizeButton.classList.add('active');
                    }
                } else {
                    const slideUpAnimation = cookieCategories.animate(
                        [
                            { opacity: 1, height: cookieCategories.offsetHeight + 'px' },
                            { opacity: 0, height: 0 }
                        ],
                        { duration: 300, easing: 'ease-out' }
                    );

                    slideUpAnimation.onfinish = function() {
                        cookieCategories.style.display = 'none';
                    };

                    if (customizeButton) {
                        customizeButton.classList.remove('active');
                    }
                }
            }

            if (cookieExists(COOKIE_NAME)) {
                hideCookieDialog();
            }

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('js-site-notice-agree')) {
                    consentWithCookies();
                } else if (event.target.classList.contains('js-site-notice-reject')) {
                    rejectAllCookies();
                } else if (event.target.classList.contains('js-site-notice-customize')) {
                    toggleCustomizeView();
                } else if (event.target.classList.contains('js-site-notice-save')) {
                    savePreferences();
                }
            });

            return {
                consentWithCookies: consentWithCookies,
                rejectAllCookies: rejectAllCookies,
                hideCookieDialog: hideCookieDialog,
                savePreferences: savePreferences,
            };
        })();
    });
</script>
