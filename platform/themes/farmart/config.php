<?php

use Botble\Base\Facades\BaseHelper;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Shortcode\View\View;
use Botble\Theme\Theme;
use Illuminate\Support\Arr;
use Illuminate\View\View as IlluminateView;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these events can be overridden by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function (Theme $theme): void {
            // You can remove this line anytime.
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme): void {
            $theme->partialComposer(['header', 'footer'], function (IlluminateView $view): void {
                $view->with('currencies', is_plugin_active('ecommerce') ? get_all_currencies() : collect());
            });

            // You may use this event to set up your assets.
            $version = get_cms_version() . '.2';

            $useCDN = theme_option('use_source_assets_from', 'cdn') == 'cdn';

            $assets = [
                'bootstrap-css' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css',
                        'attributes' => [
                            'integrity' => 'sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN',
                            'crossorigin' => 'anonymous',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/bootstrap/css/bootstrap.min.css',
                    ],
                ],
                'linearicons' => [
                    'local' => [
                        'source' => 'fonts/Linearicons/Linearicons/Font/demo-files/demo.css',
                    ],
                ],
                'slick-css' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css',
                        'attributes' => [
                            'integrity' => 'sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==',
                            'crossorigin' => 'anonymous',
                            'referrerpolicy' => 'no-referrer',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/slick/slick.css',
                    ],
                ],
                'nouislider-css' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.0.3/nouislider.min.css',
                    ],
                    'local' => [
                        'source' => 'plugins/nouislider/nouislider.min.css',
                    ],
                ],
                'lightgallery-css' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.9/css/lightgallery.min.css',
                        'attributes' => [
                            'integrity' => 'sha512-UMUaaaRK/i2ihAzNyraiLZzT8feWBDY+lLnBnhA3+MEfQn4jaNJMGBad6nyklImf7d0Id6n/Jb0ynr7RCpyNPQ==',
                            'crossorigin' => 'anonymous',
                            'referrerpolicy' => 'no-referrer',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/light-gallery/css/lightgallery.min.css',
                    ],
                ],
                'style-css' => [
                    'local' => [
                        'source' => 'css/style.css',
                        'version' => $version,
                    ],
                ],
                'jquery' => [
                    'cdn' => [
                        'source' => '//ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js',
                    ],
                    'local' => [
                        'source' => 'plugins/jquery-3.6.4.min.js',
                    ],
                    'container' => 'footer',
                ],
                'popper-js' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js',
                        'attributes' => [
                            'integrity' => 'sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB',
                            'crossorigin' => 'anonymous',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/popper.min.js',
                    ],
                    'container' => 'footer',
                ],
                'bootstrap-js' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js',
                        'attributes' => [
                            'integrity' => 'sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+',
                            'crossorigin' => 'anonymous',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/bootstrap/js/bootstrap.min.js',
                    ],
                    'container' => 'footer',
                ],
                'slick-js' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js',
                        'attributes' => [
                            'integrity' => 'sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==',
                            'crossorigin' => 'anonymous',
                            'referrerpolicy' => 'no-referrer',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/slick/slick.min.js',
                    ],
                    'container' => 'footer',
                ],
                'nouislider-js' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.0.3/nouislider.min.js',
                    ],
                    'local' => [
                        'source' => 'plugins/nouislider/nouislider.min.js',
                        'dependencies' => ['jquery'],
                    ],
                    'container' => 'footer',
                ],
                'lightgallery-js' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.9/js/lightgallery.min.js',
                        'attributes' => [
                            'integrity' => 'sha512-npsyW6Y41omzDDDg6pQtcW/pvdj4mkTG3a0LBofGH4EEYeX/LsdJgII2bu4s+O7cRcW1qBUkIz2YFZS/Rk6T3A==',
                            'crossorigin' => 'anonymous',
                            'referrerpolicy' => 'no-referrer',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/light-gallery/js/lightgallery.min.js',
                        'dependencies' => ['jquery'],
                    ],
                    'container' => 'footer',
                ],
                'lazyload-js' => [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/17.8.3/lazyload.min.js',
                        'dependencies' => ['jquery'],
                    ],
                    'local' => [
                        'source' => 'plugins/lazyload/lazyload.min.js',
                        'dependencies' => ['jquery'],
                    ],
                    'container' => 'footer',
                ],
                'expire-countdown-js' => [
                    'local' => [
                        'source' => 'plugins/expire-countdown.js',
                        'dependencies' => ['jquery'],
                    ],
                    'container' => 'footer',
                ],
                'masonry-js' => [
                    'local' => [
                        'source' => 'plugins/masonry.pkgd.min.js',
                        'dependencies' => ['jquery'],
                    ],
                    'container' => 'footer',
                ],
                'scrollbar-js' => [
                    'local' => [
                        'source' => 'plugins/scrollbar.js',
                        'dependencies' => ['jquery'],
                    ],
                    'container' => 'footer',
                ],
                'main-js' => [
                    'local' => [
                        'source' => 'js/main.js',
                        'dependencies' => ['jquery', 'bootstrap-js', 'front-ecommerce-js', 'scrollbar-js'],
                        'version' => $version,
                    ],
                    'container' => 'footer',
                ],
            ];

            if (BaseHelper::isRtlEnabled()) {
                $assets['bootstrap-css'] = [
                    'cdn' => [
                        'source' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.rtl.min.css',
                        'attributes' => [
                            'integrity' => 'sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv',
                            'crossorigin' => 'anonymous',
                        ],
                    ],
                    'local' => [
                        'source' => 'plugins/bootstrap/css/bootstrap.rtl.min.css',
                    ],
                ];

                $assets['style-rtl'] = [
                    'local' => [
                        'source' => 'css/style-rtl.css',
                        'version' => $version,
                    ],
                ];
            }

            foreach ($assets as $key => $asset) {
                $assetContainer = $theme->asset()->container(Arr::get($asset, 'container', 'default'));

                if ($useCDN && Arr::has($asset, 'cdn')) {
                    $data = $asset['cdn'];
                } else {
                    $data = $asset['local'];
                    $assetContainer->usePath(Arr::get($data, 'use_path', true));
                }

                $assetContainer->add($key, $data['source'], Arr::get($data, 'dependencies', []), Arr::get($data, 'attributes', []), Arr::get($data, 'version'));
            }

            if (function_exists('shortcode')) {
                $theme->composer([
                    'page',
                    'post',
                    'ecommerce.product',
                    'ecommerce.products',
                    'ecommerce.product-category',
                    'ecommerce.product-tag',
                    'ecommerce.brand',
                    'ecommerce.search',
                    'ecommerce.cart',
                ], function (View $view): void {
                    $view->withShortcodes();
                });
            }

            if (is_plugin_active('ecommerce')) {
                EcommerceHelper::registerThemeAssets();
            }
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function (Theme $theme): void {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            },
        ],
    ],
];
