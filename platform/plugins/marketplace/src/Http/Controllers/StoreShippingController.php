<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Location\Models\Country;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Services\VendorShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreShippingController extends BaseController
{
    public function __construct(
        protected VendorShippingService $vendorShippingService
    ) {}

    public function index(Store $store)
    {
        $this->checkPermission('vendor.shipping.manage');

        PageTitle::setTitle(trans('plugins/marketplace::store-shipping.manage', ['name' => $store->name]));

        $assignedCountries = $store->activeShippingCountries()->pluck('countries.id')->toArray();
        $availableCountries = $this->vendorShippingService->getAllAvailableCountries();

        return view('plugins/marketplace::store-shipping.index', compact('store', 'assignedCountries', 'availableCountries'));
    }

    public function store(Store $store, Request $request, BaseHttpResponse $response)
    {
        $this->checkPermission('vendor.shipping.manage');

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
                ->setMessage(trans('plugins/marketplace::store-shipping.updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return $response
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function bulkAssign(Request $request, BaseHttpResponse $response)
    {
        $this->checkPermission('vendor.shipping.manage');

        $request->validate([
            'store_ids' => 'required|array',
            'store_ids.*' => 'exists:mp_stores,id',
            'countries' => 'required|array',
            'countries.*' => 'exists:countries,id',
        ]);

        DB::beginTransaction();

        try {
            $this->vendorShippingService->bulkAssignCountries(
                $request->input('store_ids'),
                $request->input('countries')
            );

            DB::commit();

            return $response
                ->setMessage(trans('plugins/marketplace::store-shipping.bulk_updated_successfully', [
                    'count' => count($request->input('store_ids')),
                ]));
        } catch (\Exception $e) {
            DB::rollBack();

            return $response
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    protected function checkPermission(string $permission): void
    {
        if (!$this->hasPermission($permission)) {
            abort(403);
        }
    }
}
