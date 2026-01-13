<?php

namespace Botble\Ecommerce\Http\Middleware;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Closure;
use Illuminate\Http\Request;

class CheckProductSpecificationEnabledMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(EcommerceHelper::isProductSpecificationEnabled(), 404);

        return $next($request);
    }
}
