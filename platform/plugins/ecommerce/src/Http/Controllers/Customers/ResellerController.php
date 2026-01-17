<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Http\Request;

class ResellerController extends BaseController
{
    public function toggleStatus(Request $request)
    {
        $customer = auth('customer')->user();
        
        if (!$customer->is_reseller) {
            return response()->json([
                'error' => true,
                'message' => __('You are not a reseller.'),
            ]);
        }

        // Toggle the active status
        $customer->is_reseller_active = !$customer->is_reseller_active;
        $customer->save();

        return response()->json([
            'error' => false,
            'message' => $customer->is_reseller_active ? __('Reseller status enabled.') : __('Reseller status disabled.'),
        ]);
    }

    public function requestDelete(Request $request)
    {
        $customer = auth('customer')->user();
        
        if (!$customer->is_reseller) {
            return response()->json([
                'error' => true,
                'message' => __('You are not a reseller.'),
            ]);
        }
        
        $customer->reseller_deletion_requested_at = now();
        $customer->save();

        return response()->json([
            'error' => false,
            'message' => __('Your deletion request has been submitted.'),
        ]);
    }
}
