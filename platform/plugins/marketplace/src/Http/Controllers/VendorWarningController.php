<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Models\VendorWarning;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Enums\WarningLevelEnum;
use Botble\Marketplace\Http\Requests\VendorWarningRequest;
use Botble\Marketplace\Notifications\VendorWarningNotification;
use Botble\Base\Supports\Breadcrumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorWarningController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('Vendor Warnings'), route('marketplace.vendor-warnings.index'));
    }

    public function index(Request $request)
    {
        $this->pageTitle(trans('Vendor Warnings'));

        $warnings = VendorWarning::query()
            ->with(['store', 'issuedBy'])
            ->when($request->input('store_id'), function ($query, $storeId) {
                return $query->where('store_id', $storeId);
            })
            ->when($request->input('acknowledged'), function ($query, $acknowledged) {
                return $query->where('acknowledged', $acknowledged === 'true');
            })
            ->latest()
            ->paginate(20);

        return view('plugins/marketplace::warnings.index', compact('warnings'));
    }

    public function create(Request $request)
    {
        $this->pageTitle(trans('Issue Vendor Warning'));

        $storeId = $request->input('store_id');
        $store = $storeId ? Store::find($storeId) : null;
        $stores = Store::query()->pluck('name', 'id');

        return view('plugins/marketplace::warnings.create', compact('store', 'stores'));
    }

    public function store(VendorWarningRequest $request): BaseHttpResponse
    {
        $warning = VendorWarning::create([
            'store_id' => $request->input('store_id'),
            'issued_by' => Auth::id(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'severity' => $request->input('severity', WarningLevelEnum::WARNING),
            'acknowledged' => false,
        ]);

        // Send email notification if requested
        if ($request->input('send_email', false)) {
            $store = $warning->store;
            if ($store && $store->customer) {
                $store->customer->notify(new VendorWarningNotification($warning));
                $warning->update(['email_sent' => true]);
            }
        }

        return $this
            ->httpResponse()
            ->setMessage(__('Warning issued successfully'))
            ->setNextUrl(route('marketplace.vendor-warnings.index'));
    }

    public function show(int $id)
    {
        $this->pageTitle(trans('Warning Details'));

        $warning = VendorWarning::with(['store', 'issuedBy'])->findOrFail($id);

        return view('plugins/marketplace::warnings.show', compact('warning'));
    }

    public function destroy(int $id): BaseHttpResponse
    {
        $warning = VendorWarning::findOrFail($id);
        $warning->delete();

        return $this
            ->httpResponse()
            ->setMessage(__('Warning deleted successfully'));
    }
}
