<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Facades\EmailHandler;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Enums\StoreStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VendorBlockedController extends BaseController
{
    public function store(string $id, Request $request)
    {
        $customer = Customer::query()
            ->where('status', CustomerStatusEnum::ACTIVATED)
            ->where('is_vendor', true)
            ->findOrFail($id);

        $request->validate([
            'reason' => ['required', 'string', 'max:400'],
        ]);

        $customer->block_reason = $request->input('reason');
        $customer->status = CustomerStatusEnum::LOCKED;
        $customer->save();

        $store = $customer->store;

        if ($store->exists()) {
            $store->update(['status' => StoreStatusEnum::BLOCKED]);
        }

        EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'block_reason' => $customer->block_reason,
                'block_date' => Carbon::now(),
                'store_name' => $store->name,
                'store_phone' => $store->phone,
                'store_address' => $store->full_address,
                'store_url' => $store->url,
            ])
            ->sendUsingTemplate('vendor-account-blocked', $customer->email);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/marketplace::store.control.blocked_success'));
    }

    public function destroy(string $id)
    {
        $customer = Customer::query()
            ->where('status', CustomerStatusEnum::LOCKED)
            ->where('is_vendor', true)
            ->findOrFail($id);

        $customer->block_reason = null;
        $customer->status = CustomerStatusEnum::ACTIVATED;
        $customer->save();

        $store = $customer->store;

        if ($store->exists()) {
            $store->update(['status' => StoreStatusEnum::PUBLISHED]);
        }

        EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'store_name' => $store->name,
                'store_phone' => $store->phone,
                'store_address' => $store->full_address,
                'store_url' => $store->url,
                'unblock_date' => Carbon::now(),
            ])
            ->sendUsingTemplate('vendor-account-unblocked', $customer->email);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/marketplace::store.control.unblocked_success'));
    }
}
