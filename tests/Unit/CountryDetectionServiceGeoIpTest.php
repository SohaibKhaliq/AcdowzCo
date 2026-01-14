<?php

namespace Tests\Unit;

use Tests\TestCase;

class CountryDetectionServiceGeoIpTest extends TestCase
{
    public function test_geoip_provider_removed(): void
    {
        $this->markTestSkipped('torann/geoip removed; test no longer applicable.');
    }
}
