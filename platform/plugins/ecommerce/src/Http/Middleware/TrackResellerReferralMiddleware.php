<?php

namespace Botble\Ecommerce\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackResellerReferralMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if there's a referral code in the URL
        if ($request->has('ref')) {
            $referralCode = $request->input('ref');
            
            // Validate that the referral code exists and is active
            $reseller = \Botble\Ecommerce\Models\Customer::where('reseller_id', $referralCode)
                ->where('is_reseller_active', true)
                ->first();
            
            if ($reseller) {
                // Store in session for 30 days
                session(['referral_code' => $referralCode]);
                cookie()->queue(cookie('referral_code', $referralCode, 60 * 24 * 30));
            }
        }
        
        // Also check cookie if not in session
        if (!session('referral_code') && $request->cookie('referral_code')) {
            session(['referral_code' => $request->cookie('referral_code')]);
        }
        
        return $next($request);
    }
}
