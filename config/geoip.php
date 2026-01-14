<?php

return [
    // Enable/disable external GeoIP lookups (ipapi fallback)
    'enabled' => env('GEOIP_ENABLED', true),

    // Timeout for external fallback calls
    'fallback_timeout' => env('GEOIP_FALLBACK_TIMEOUT', 3),
];
