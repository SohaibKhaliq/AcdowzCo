<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Models\VendorSubscription;
use Botble\Marketplace\Tables\VendorSubscriptionTable;
use Illuminate\Http\Request;

class VendorSubscriptionController extends BaseController
{
    public function index(VendorSubscriptionTable $table)
    {
        PageTitle::setTitle(trans('plugins/marketplace::subscription.subscriptions.name'));

        return $table->renderTable();
    }

    public function show(int|string $id)
    {
        $subscription = VendorSubscription::query()
            ->with(['customer', 'store', 'plan'])
            ->findOrFail($id);

        PageTitle::setTitle(trans('plugins/marketplace::subscription.subscriptions.view', ['id' => $id]));

        return view('plugins/marketplace::subscriptions.show', compact('subscription'));
    }

    public function cancel(int|string $id)
    {
        $subscription = VendorSubscription::query()->findOrFail($id);

        $subscription->update(['status' => 'cancelled']);

        // Remove verified badge if subscription had verification eligibility
        if ($subscription->plan && $subscription->plan->verified_eligible) {
            $store = $subscription->store;
            if ($store) {
                $store->update([
                    'is_verified' => false,
                    'verified_at' => null,
                    'verification_note' => 'Verification removed due to subscription cancellation',
                ]);
            }
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.vendor-subscriptions.index'))
            ->setMessage(trans('plugins/marketplace::subscription.subscriptions.cancelled_success'));
    }
}
