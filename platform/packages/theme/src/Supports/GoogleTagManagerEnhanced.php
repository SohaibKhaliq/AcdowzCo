<?php

namespace Botble\Theme\Supports;

use Throwable;

class GoogleTagManagerEnhanced
{
    public static function renderGoogleTagManagerScript(): string
    {
        try {
            $debugMode = (bool) setting('gtm_debug_mode', false);
            $renderType = setting('google_tag_manager_type');

            if ($renderType === 'code') {
                $renderType = 'custom';
            }

            $script = match ($renderType) {
                'custom' => self::renderCustomTracking($debugMode),
                'gtm' => self::renderGtmContainer($debugMode),
                'id' => self::renderGoogleAnalytics($debugMode),
                default => self::renderAutoDetect($debugMode),
            };

            if ($debugMode && $script) {
                $script = self::wrapWithDebugMode($script);
            }

            return $script;
        } catch (Throwable) {
            return '';
        }
    }

    protected static function renderCustomTracking(bool $debugMode): string
    {
        $customTrackingHeaderJs = setting('custom_tracking_header_js') ?: setting('google_tag_manager_code');

        if (! $customTrackingHeaderJs) {
            return '';
        }

        $initDataLayer = '
        <script>
            window.dataLayer = window.dataLayer || [];
        </script>
        ';

        return trim($initDataLayer . "\n" . $customTrackingHeaderJs);
    }

    protected static function renderGtmContainer(bool $debugMode): string
    {
        $gtmContainerId = setting('gtm_container_id');

        if (! $gtmContainerId) {
            return '';
        }

        $debugParam = '';

        $errorHandler = $debugMode ? "
                j.onload = j.onreadystatechange = function() {
                    if (!j.readyState || /loaded|complete/.test(j.readyState)) {
                        setTimeout(function() {
                            if (window.google_tag_manager && window.google_tag_manager['$gtmContainerId']) {
                                console.log('GTM container loaded successfully: $gtmContainerId');
                                window.gtmLoaded = true;
                            } else if (window.dataLayer && window.dataLayer.length > 1) {
                                console.log('GTM container initialized: $gtmContainerId (dataLayer active)');
                                window.gtmLoaded = true;
                            } else {
                                console.warn('GTM container may not be fully loaded: $gtmContainerId. Check if the container is published and the ID is correct.');
                            }
                        }, 1000);
                    }
                };
                j.onerror = function() {
                    console.error('Failed to load GTM script from Google servers for container: $gtmContainerId');
                    if (window.gtmErrorCallback) {
                        window.gtmErrorCallback('$gtmContainerId');
                    }
                };" : '';

        return trim(
            <<<HTML
            <!-- Google Tag Manager -->
            <script>
            window.dataLayer = window.dataLayer || [];
            (function(w,d,s,l,i){
                w[l]=w[l]||[];
                w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
                var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
                j.async=true;
                j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl+'$debugParam';$errorHandler
                f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','$gtmContainerId');
            </script>
            HTML
        );
    }

    protected static function renderGoogleAnalytics(bool $debugMode): string
    {
        $googleTagManagerId = setting('google_tag_manager_id', setting('google_analytics'));

        if (! $googleTagManagerId) {
            return '';
        }

        $debugConfig = $debugMode ? "gtag('config', '{$googleTagManagerId}', { 'debug_mode': true });" : "gtag('config', '{$googleTagManagerId}');";

        $errorHandler = $debugMode ?
            "onerror=\"console.warn('Google Analytics could not be loaded: $googleTagManagerId')\"
                onload=\"console.log('Google Analytics loaded successfully: $googleTagManagerId')\"" : '';

        return trim(
            <<<HTML
            <!-- Google Analytics -->
            <script async defer src='https://www.googletagmanager.com/gtag/js?id=$googleTagManagerId'
                $errorHandler>
            </script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){
                  if (window.gtmDebugMode) {
                      console.log('GTM Event:', arguments);
                  }
                  dataLayer.push(arguments);
              }
              gtag('js', new Date());
              $debugConfig
            </script>
            HTML
        );
    }

    protected static function renderAutoDetect(bool $debugMode): string
    {
        $gtmContainerId = setting('gtm_container_id');
        if ($gtmContainerId) {
            return self::renderGtmContainer($debugMode);
        }

        $customCode = setting('custom_tracking_header_js') ?: setting('google_tag_manager_code');
        if ($customCode) {
            return self::renderCustomTracking($debugMode);
        }

        $googleTagManagerId = setting('google_tag_manager_id', setting('google_analytics'));
        if ($googleTagManagerId) {
            return self::renderGoogleAnalytics($debugMode);
        }

        return '';
    }

    protected static function wrapWithDebugMode(string $script): string
    {
        return <<<HTML
        <script>
            window.gtmDebugMode = true;
            console.log('%c GTM Debug Mode Enabled ', 'background: #4CAF50; color: white; padding: 2px 5px; border-radius: 3px;');

            (function() {
                var originalPush = Array.prototype.push;
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push = function() {
                    console.log('%c GTM Event ', 'background: #2196F3; color: white; padding: 2px 5px; border-radius: 3px;', arguments);
                    return originalPush.apply(this, arguments);
                };
            })();

            window.addEventListener('load', function() {
                setTimeout(function() {
                    var gtmContainerId = (function() {
                        var scripts = document.getElementsByTagName('script');
                        for (var i = 0; i < scripts.length; i++) {
                            var src = scripts[i].src;
                            if (src && src.includes('googletagmanager.com/gtm.js')) {
                                var match = src.match(/[?&]id=([^&]+)/);
                                return match ? match[1] : null;
                            }
                        }
                        return null;
                    })();

                    if (window.google_tag_manager && gtmContainerId && window.google_tag_manager[gtmContainerId]) {
                        console.log('%c ✓ GTM Container ' + gtmContainerId + ' Loaded Successfully ', 'background: #4CAF50; color: white; padding: 2px 5px; border-radius: 3px;');
                        console.log('GTM Object:', window.google_tag_manager[gtmContainerId]);
                    } else if (window.gtmLoaded) {
                        console.log('%c ✓ GTM Loaded (verified by load handler) ', 'background: #4CAF50; color: white; padding: 2px 5px; border-radius: 3px;');
                    } else if (typeof gtag === 'function') {
                        console.log('%c ✓ Google Analytics Loaded Successfully ', 'background: #4CAF50; color: white; padding: 2px 5px; border-radius: 3px;');
                    } else if (window.dataLayer && window.dataLayer.length > 1) {
                        console.log('%c ✓ DataLayer is active with ' + window.dataLayer.length + ' events ', 'background: #4CAF50; color: white; padding: 2px 5px; border-radius: 3px;');
                        console.log('DataLayer contents:', window.dataLayer);
                    } else {
                        console.info('%c ℹ GTM/Analytics may still be initializing or container may not be published ', 'background: #FF9800; color: white; padding: 2px 5px; border-radius: 3px;');
                    }
                }, 3000);
            });
        </script>
        $script
        HTML;
    }

    public static function renderGoogleTagManagerNoscript(): string
    {
        try {
            $renderType = setting('google_tag_manager_type');

            if ($renderType === 'code') {
                $renderType = 'custom';
            }

            return match ($renderType) {
                'custom' => self::renderCustomNoscript(),
                'gtm' => self::renderGtmNoscript(),
                default => self::renderAutoDetectNoscript(),
            };
        } catch (Throwable) {
            return '';
        }
    }

    protected static function renderCustomNoscript(): string
    {
        $customTrackingBodyHtml = setting('custom_tracking_body_html');

        return $customTrackingBodyHtml ? trim($customTrackingBodyHtml) : '';
    }

    protected static function renderGtmNoscript(): string
    {
        $gtmContainerId = setting('gtm_container_id');

        if (! $gtmContainerId) {
            return '';
        }

        return trim(
            <<<HTML
            <!-- Google Tag Manager -->
            <noscript>
                <iframe src="https://www.googletagmanager.com/ns.html?id=$gtmContainerId"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
            </noscript>
            HTML
        );
    }

    protected static function renderAutoDetectNoscript(): string
    {
        $gtmContainerId = setting('gtm_container_id');
        if ($gtmContainerId) {
            return self::renderGtmNoscript();
        }

        $customTrackingBodyHtml = setting('custom_tracking_body_html');
        if ($customTrackingBodyHtml) {
            return self::renderCustomNoscript();
        }

        return '';
    }
}
