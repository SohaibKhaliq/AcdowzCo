<?php

namespace Tests\Unit;

// Load the plugin service class for testing (platform path)
require_once __DIR__ . '/../../platform/plugins/ecommerce/src/Services/Location/CountryDetectionService.php';

use Botble\Ecommerce\Services\Location\CountryDetectionService;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class CountryDetectionServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_detect_from_ip_uses_fallback_api_and_maps_to_country(): void
    {
        // Fake the external API response
        Http::fake([
            'ipapi.co/*' => Http::response([
                'ip' => '1.2.3.4',
                'country' => 'US',
            ], 200),
        ]);

        // Mock the Country model query builder chain
        $builder = Mockery::mock();
        $builder->shouldReceive('where')->with('iso_code', 'US')->andReturnSelf();
        $builder->shouldReceive('where')->with('status', 'published')->andReturnSelf();
        $builder->shouldReceive('first')->andReturn((object)['id' => 999]);

        $country = Mockery::mock('alias:Botble\\Location\\Models\\Country');
        $country->shouldReceive('query')->andReturn($builder);

        // Ensure request has an IP
        request()->server->set('REMOTE_ADDR', '1.2.3.4');

        $service = new CountryDetectionService();

        $result = $service->detectCountry(null);

        $this->assertSame(999, $result);
    }

    public function test_detect_from_ip_returns_null_for_local_ip(): void
    {
        request()->server->set('REMOTE_ADDR', '127.0.0.1');

        $service = new CountryDetectionService();

        // Call protected detectFromIp via reflection to avoid falling back to default country
        $method = new \ReflectionMethod(CountryDetectionService::class, 'detectFromIp');
        $method->setAccessible(true);

        $this->assertNull($method->invoke($service));
    }
}
