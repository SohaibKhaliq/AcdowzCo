<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Models\Vendor;
use Botble\Marketplace\Tables\VendorTable;
use Illuminate\Support\Facades\Storage;

class VendorController extends BaseController
{
    public function index(VendorTable $table)
    {
        $this->pageTitle(trans('plugins/marketplace::marketplace.vendors'));

        return $table->renderTable();
    }

    public function view($id)
    {
        $vendor = Vendor::query()
            ->with(['store', 'orders', 'addresses', 'wishlist', 'reviews', 'revenues', 'withdrawals'])
            ->findOrFail($id);

        $this->pageTitle(trans('plugins/marketplace::marketplace.view_vendor', ['name' => $vendor->name]));

        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/customer.js');

        $totalSpent = $vendor->completedOrders()->sum('amount');
        $totalOrders = $vendor->orders()->count();
        $completedOrders = $vendor->completedOrders()->count();
        $totalProducts = $vendor->completedOrders()
            ->withCount('products')
            ->get()
            ->sum('products_count');

        $store = $vendor->store;
        $totalRevenue = $vendor->total_revenue;
        $totalEarnings = $vendor->total_earnings;
        $totalWithdrawals = $vendor->completed_withdrawals;
        $pendingWithdrawals = $vendor->pending_withdrawals;
        $balance = $vendor->balance;
        $storeProducts = $vendor->products_count;
        $storeOrders = $vendor->orders_count;

        return view('plugins/marketplace::vendors.view', compact(
            'vendor',
            'totalSpent',
            'totalOrders',
            'completedOrders',
            'totalProducts',
            'store',
            'totalRevenue',
            'totalEarnings',
            'totalWithdrawals',
            'pendingWithdrawals',
            'balance',
            'storeProducts',
            'storeOrders'
        ));
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
