<?php

namespace Botble\Ecommerce\Http\Middleware;

use Botble\Ecommerce\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionalApiAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            try {
                $user = Auth::guard('sanctum')->user();

                if ($user && $user instanceof Customer) {
                    Auth::guard('customer')->login($user);
                    Auth::setUser($user);
                }
            } catch (\Exception $e) {
                logger()->debug('Optional API authentication failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
