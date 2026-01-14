<?php

namespace Botble\Ecommerce\Http\Middleware;

use Botble\Ecommerce\Services\Location\CountryDetectionService;
use Botble\Ecommerce\Services\Location\ProductAvailabilityService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyCountryFilterMiddleware
{
    public function __construct(
        protected CountryDetectionService $countryDetectionService,
        protected ProductAvailabilityService $productAvailabilityService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $countryId = $this->countryDetectionService->getCurrentCountryId();
        
        // Share country filter status with views
        view()->share('countryFilterEnabled', (bool) $countryId);
        view()->share('currentCountryFilter', $countryId);

        return $next($request);
    }
}
