<?php

namespace Botble\Marketplace\Http\Controllers\Vendor;

use Botble\Marketplace\Http\Controllers\BaseController;
use Botble\Marketplace\Models\SubscriptionPlan;
use Botble\Marketplace\Models\VendorSubscription;
use Botble\Marketplace\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends BaseController
{
    public function index()
    {
        $vendor = Vendor::query()->findOrFail(Auth::guard('customer')->id());
        $store = $vendor->store;

        if (!$store) {
            return redirect()->route('marketplace.vendor.dashboard')->with('error', trans('plugins/marketplace::subscription.no_store'));
        }

        $plans = SubscriptionPlan::query()
            ->where('status', true)
            ->orderBy('price')
            ->get();

        $currentSubscription = VendorSubscription::query()
            ->where('customer_id', $vendor->id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->with('plan')
            ->first();

        return view('plugins/marketplace::vendor.subscriptions.index', compact('plans', 'currentSubscription', 'store'));
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:mp_subscription_plans,id',
        ]);

        $vendor = Vendor::query()->findOrFail(Auth::guard('customer')->id());
        $store = $vendor->store;

        if (!$store) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/marketplace::subscription.no_store'));
        }

        $plan = SubscriptionPlan::query()->findOrFail($validated['plan_id']);

        if (!$plan->isActive()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/marketplace::subscription.plan_not_available'));
        }

        // Check if vendor already has an active subscription
        $existingSubscription = VendorSubscription::query()
            ->where('customer_id', $vendor->id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($existingSubscription) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/marketplace::subscription.already_subscribed'));
        }

        $startsAt = Carbon::now();
        $expiresAt = $startsAt->copy()->addDays($plan->duration_in_days);

        $subscription = VendorSubscription::query()->create([
            'customer_id' => $vendor->id,
            'store_id' => $store->id,
            'plan_id' => $plan->id,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);

        // Update store verification eligibility
        if ($plan->verified_eligible && !$store->is_verified) {
            // Store can now apply for verification
            // Verification is not automatic, needs admin approval
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('marketplace.vendor.subscriptions.index'))
            ->setMessage(trans('plugins/marketplace::subscription.subscribed_success'));
    }

    public function renew(int|string $id)
    {
        $vendor = Vendor::query()->findOrFail(Auth::guard('customer')->id());
        
        $subscription = VendorSubscription::query()
            ->where('id', $id)
            ->where('customer_id', $vendor->id)
            ->firstOrFail();

        $plan = $subscription->plan;

        $startsAt = Carbon::now();
        $expiresAt = $startsAt->copy()->addDays($plan->duration_in_days);

        $newSubscription = VendorSubscription::query()->create([
            'customer_id' => $vendor->id,
            'store_id' => $subscription->store_id,
            'plan_id' => $plan->id,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);

        // Mark old subscription as expired
        $subscription->markAsExpired();

        return $this
            ->httpResponse()
            ->setNextUrl(route('marketplace.vendor.subscriptions.index'))
            ->setMessage(trans('plugins/marketplace::subscription.renewed_success'));
    }
}
