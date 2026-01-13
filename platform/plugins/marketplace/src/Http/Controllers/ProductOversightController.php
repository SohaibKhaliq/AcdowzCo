<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Product;
use Botble\Marketplace\Models\Store;
use Illuminate\Http\Request;

class ProductOversightController extends BaseController
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['store', 'approvedBy'])
            ->whereNotNull('store_id')
            ->when($request->input('vendor_id'), function ($q, $vendorId) {
                return $q->where('store_id', $vendorId);
            })
            ->when($request->input('status'), function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->input('approved_status'), function ($q, $approved) {
                if ($approved === 'pending') {
                    return $q->whereNull('approved_by');
                } elseif ($approved === 'approved') {
                    return $q->whereNotNull('approved_by');
                }
            });

        $products = $query->latest()->paginate(50);
        $stores = Store::query()->pluck('name', 'id');

        return view('plugins/marketplace::product-oversight.index', compact('products', 'stores'));
    }

    public function approve(int $id): BaseHttpResponse
    {
        $product = Product::findOrFail($id);
        
        $product->update([
            'approved_by' => auth()->id(),
            'status' => 'published',
        ]);

        return $this
            ->httpResponse()
            ->setMessage(__('Product approved successfully'));
    }

    public function reject(int $id, Request $request): BaseHttpResponse
    {
        $product = Product::findOrFail($id);
        
        $product->update([
            'status' => 'draft',
            'approved_by' => null,
        ]);

        // Optionally send notification to vendor
        if ($request->input('reason')) {
            // Store rejection reason in product metadata or notifications
        }

        return $this
            ->httpResponse()
            ->setMessage(__('Product rejected'));
    }

    public function bulkDelete(Request $request): BaseHttpResponse
    {
        $productIds = $request->input('ids', []);
        
        if (empty($productIds)) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('No products selected'));
        }

        Product::whereIn('id', $productIds)->delete();

        return $this
            ->httpResponse()
            ->setMessage(__('Products deleted successfully'));
    }

    public function getRevenueModel(int $productId): BaseHttpResponse
    {
        $product = Product::with('store')->findOrFail($productId);
        
        if (!$product->store) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('No store associated with this product'));
        }

        $store = $product->store;
        
        return $this
            ->httpResponse()
            ->setData([
                'agreement_type' => $store->agreement_type,
                'agreement_value' => $store->agreement_value,
                'agreement_notes' => $store->agreement_notes,
            ]);
    }
}
