<?php

namespace Botble\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

readonly class EnsureLicenseHasBeenActivated
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
