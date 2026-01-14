<?php

namespace Botble\Marketplace\Http\Controllers\Vendor;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Services\VendorShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShippingCountryController extends BaseController
{
    public function __construct(
        protected VendorShippingService $vendorShippingService
    ) {}

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $store = $customer->store;

        if (!$store) {
            abort(404, trans('plugins/marketplace::store.store_not_found'));
        }

        PageTitle::setTitle(trans('plugins/marketplace::store-shipping.vendor_title'));

        $assignedCountries = $store->activeShippingCountries()->pluck('countries.id')->toArray();
        $availableCountries = $this->vendorShippingService->getAllAvailableCountries();

        return view('plugins/marketplace::vendor.shipping-countries.index', compact('store', 'assignedCountries', 'availableCountries'));
    }

    public function update(Request $request, BaseHttpResponse $response)
    {
        $customer = Auth::guard('customer')->user();
        $store = $customer->store;

        if (!$store) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/marketplace::store.store_not_found'));
        }

        $request->validate([
            'countries' => 'nullable|array',
            'countries.*' => 'exists:countries,id',
        ]);

        DB::beginTransaction();

        try {
            $this->vendorShippingService->updateShippingCountries(
                $store,
                $request->input('countries', [])
            );

            DB::commit();

            return $response
                ->setMessage(trans('plugins/marketplace::store-shipping.vendor_updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return $response
                ->setError()
                ->setMessage($e->getMessage());
        }
    }
}
