<?php

namespace Botble\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if security headers are enabled in configuration
        if (! config('core.base.general.enable_http_security_headers', true)) {
            return $response;
        }

        // Add security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Ensure session cookies have proper flags
        if ($response instanceof Response) {
            $cookies = $response->headers->getCookies();

            foreach ($cookies as $cookie) {
                // Session cookies should always have HttpOnly flag
                if (str_contains($cookie->getName(), 'session') ||
                    $cookie->getName() === config('session.cookie')) {
                    // Cookie is already set with proper flags from session config
                    continue;
                }
            }
        }

        return $response;
    }
}
