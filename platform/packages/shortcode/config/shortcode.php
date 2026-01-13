<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shortcode Caching
    |--------------------------------------------------------------------------
    |
    | This option controls the caching of shortcodes. When enabled, shortcodes
    | will be cached to improve performance. You can specify which shortcodes
    | should be cached and for how long.
    |
    */
    'cache' => [
        'enabled' => env('SHORTCODE_CACHE_ENABLED', true),
        'ttl' => env('SHORTCODE_CACHE_TTL', 1800), // 30 minutes
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | This option controls the performance monitoring of shortcodes. When enabled,
    | the system will log warnings when shortcodes take too long to render.
    |
    */
    'performance' => [
        'log_threshold' => env('SHORTCODE_PERFORMANCE_LOG_THRESHOLD', 0.5), // seconds
    ],
];
