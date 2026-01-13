<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Models\Vendor;
use Botble\Marketplace\Tables\UnverifiedVendorTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnverifiedVendorController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/marketplace::unverified-vendor.name'), route('marketplace.unverified-vendors.index'));
    }

    public function index(UnverifiedVendorTable $table)
    {
        $this->pageTitle(trans('plugins/marketplace::unverified-vendor.name'));

        return $table->renderTable();
    }

    public function view(int|string $id)
    {
        $vendor = Vendor::query()->findOrFail($id);

        if ($vendor->vendor_verified_at) {
            return redirect()->route('customers.edit', $vendor->getKey());
        }

        $this->pageTitle(trans('plugins/marketplace::unverified-vendor.verify', ['name' => $vendor->name]));

        Assets::addScriptsDirectly(['vendor/core/plugins/marketplace/js/marketplace-vendor.js']);

        return view('plugins/marketplace::customers.verify-vendor', compact('vendor'));
    }

    public function approveVendor(int|string $id, Request $request)
    {
        $vendor = Vendor::query()
            ->unverified()
            ->findOrFail($id);

        $vendor->verify();

        event(new UpdatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $vendor));

        if (MarketplaceHelper::getSetting('verify_vendor', 1) && ($vendor->store->email || $vendor->email)) {
            EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'store_name' => $vendor->store->name,
                ])
                ->sendUsingTemplate('vendor-account-approved', $vendor->store->email ?: $vendor->email);
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.unverified-vendors.index'))
            ->withUpdatedSuccessMessage();
    }

    public function rejectVendor(int|string $id)
    {
        $vendor = Vendor::query()
            ->unverified()
            ->findOrFail($id);

        $store = $vendor->store;

        $vendor->is_vendor = false;
        $vendor->save();

        $storage = Storage::disk('local');

        if ($vendor->store->certificate_file && $storage->exists($vendor->store->certificate_file)) {
            $storage->delete($vendor->store->certificate_file);
        }

        if ($vendor->store->government_id_file && $storage->exists($vendor->store->government_id_file)) {
            $storage->delete($vendor->store->government_id_file);
        }

        if (MarketplaceHelper::getSetting('verify_vendor', 1) && ($vendor->store->email || $vendor->email)) {
            EmailHandler::setModule(MARKETPLACE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'store_name' => $vendor->store->name,
                ])
                ->sendUsingTemplate('vendor-account-rejected', $vendor->store->email ?: $vendor->email);
        }

        $store->delete();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('marketplace.unverified-vendors.index'))
            ->withDeletedSuccessMessage();
    }

    public function downloadCertificate(int|string $id)
    {
        $vendor = Vendor::query()->findOrFail($id);

        $storage = Storage::disk('local');

        if (! $storage->exists($vendor->store->certificate_file)) {
            return BaseHttpResponse::make()
                ->setError()
                ->setMessage(__('File not found!'));
        }

        return response()->file($storage->path($vendor->store->certificate_file));
    }

    public function downloadGovernmentId(int|string $id)
    {
        $vendor = Vendor::query()->findOrFail($id);

        $storage = Storage::disk('local');

        if (! $storage->exists($vendor->store->government_id_file)) {
            return BaseHttpResponse::make()
                ->setError()
                ->setMessage(__('File not found!'));
        }

        return response()->file($storage->path($vendor->store->government_id_file));
    }
}
