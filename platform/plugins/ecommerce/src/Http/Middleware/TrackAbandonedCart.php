<?php

namespace Botble\Ecommerce\Http\Middleware;

use Botble\Ecommerce\Services\AbandonedCartService;
use Closure;
use Illuminate\Http\Request;

class TrackAbandonedCart
{
    public function __construct(
        protected AbandonedCartService $abandonedCartService
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->ajax() || $request->wantsJson()) {
            return $response;
        }

        if ($request->route() && in_array($request->route()->getName(), [
            'public.cart',
            'public.cart.update',
            'public.cart.add-to-cart',
            'public.ajax.cart.update',
        ])) {
            $this->abandonedCartService->trackCart();
        }

        if ($request->isMethod('post') && $request->has(['email', 'name'])) {
            $this->abandonedCartService->updateCustomerInfo([
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'name' => $request->input('name'),
            ]);
        }

        return $response;
    }
}
