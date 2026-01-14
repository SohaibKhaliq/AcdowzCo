<?php

namespace Botble\Ecommerce\Http\Middleware;

use Botble\Ecommerce\Services\Location\CountryDetectionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DetectUserCountryMiddleware
{
    public function __construct(
        protected CountryDetectionService $countryDetectionService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Detect and set country for user
        $customer = Auth::guard('customer')->user();
        
        // Auto-detect if no country is set
        $countryId = $this->countryDetectionService->detectCountry($customer);
        
        // Store in view for easy access
        view()->share('currentCountryId', $countryId);
        view()->share('currentCountryName', $this->countryDetectionService->getCountryName($countryId));

        return $next($request);
    }
}
