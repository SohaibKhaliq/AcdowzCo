<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Models\VendorWarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorWarningController extends BaseController
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $store = $customer->store;

        if (!$store) {
            abort(404);
        }

        $warnings = VendorWarning::where('store_id', $store->id)
            ->latest()
            ->paginate(10);

        return MarketplaceHelper::view('vendor-dashboard.warnings.index', compact('warnings'));
    }

    public function show(int|string $id)
    {
        $customer = Auth::guard('customer')->user();
        $store = $customer->store;

        $warning = VendorWarning::where('store_id', $store->id)->findOrFail($id);

        return MarketplaceHelper::view('vendor-dashboard.warnings.show', compact('warning'));
    }

    public function acknowledge(int|string $id): BaseHttpResponse
    {
        $customer = Auth::guard('customer')->user();
        $store = $customer->store;

        $warning = VendorWarning::where('store_id', $store->id)->findOrFail($id);

        if (!$warning->acknowledged) {
            $warning->update([
                'acknowledged' => true,
                'acknowledged_at' => now(),
            ]);
        }

        return $this->httpResponse()
            ->setMessage(__('Warning acknowledged successfully'))
            ->setNextUrl(route('marketplace.vendor.warnings.index'));
    }
}
