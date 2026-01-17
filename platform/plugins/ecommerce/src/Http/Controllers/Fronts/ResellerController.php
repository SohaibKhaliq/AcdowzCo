<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\ResellerOrder;
use Botble\Ecommerce\Models\ResellerClick;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResellerController extends BaseController
{
    public function dashboard()
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        if (!$customer->is_reseller_active) {
            return redirect()->route('customer.reseller.apply');
        }

        $stats = [
            'total_clicks' => ResellerClick::where('reseller_id', $customer->id)->count(),
            'total_orders' => ResellerOrder::where('reseller_id', $customer->id)->count(),
            'pending_commission' => ResellerOrder::where('reseller_id', $customer->id)
                ->where('status', 'pending')
                ->sum('commission_earned'),
            'approved_commission' => ResellerOrder::where('reseller_id', $customer->id)
                ->where('status', 'approved')
                ->sum('commission_earned'),
            'paid_commission' => ResellerOrder::where('reseller_id', $customer->id)
                ->where('status', 'paid')
                ->sum('commission_earned'),
        ];

        $recentClicks = ResellerClick::where('reseller_id', $customer->id)
            ->with('product')
            ->latest('clicked_at')
            ->limit(10)
            ->get();

        $recentOrders = ResellerOrder::where('reseller_id', $customer->id)
            ->with('order')
            ->latest()
            ->limit(10)
            ->get();

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Reseller Dashboard'), route('customer.reseller.dashboard'));

        return Theme::scope('ecommerce.reseller-dashboard', compact('customer', 'stats', 'recentClicks', 'recentOrders'))
            ->render();
    }

    public function apply()
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        if ($customer->is_reseller_active) {
            return redirect()->route('customer.reseller.dashboard');
        }

        $application = $customer->resellerApplications()->latest()->first();

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Apply for Reseller Program'), route('customer.reseller.apply'));

        return Theme::scope('ecommerce.reseller.apply', compact('customer', 'application'))
            ->render();
    }

    public function postApply(Request $request, BaseHttpResponse $response)
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        if ($customer->is_reseller_active) {
            return $response->setError()->setMessage(__('You are already a reseller.'));
        }

        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $existingApplication = $customer->resellerApplications()->where('status', 'pending')->first();
        if ($existingApplication) {
            return $response->setError()->setMessage(__('You already have a pending application.'));
        }

        $customer->resellerApplications()->create([
            'notes' => $request->input('notes'),
            'status' => 'pending',
        ]);

        return $response->setMessage(__('Your application has been submitted successfully and is pending approval.'));
    }

    public function toggleStatus(Request $request): BaseHttpResponse
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Unauthorized'));
        }

        $customer->is_reseller_active = !$customer->is_reseller_active;
        $customer->save();

        return $this
            ->httpResponse()
            ->setMessage(__('Reseller status updated successfully'));
    }

    public function requestDelete(Request $request): BaseHttpResponse
    {
        $customer = auth('customer')->user();

        if (!$customer || !$customer->is_reseller_active) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Reseller mode is not active'));
        }

        $customer->reseller_deletion_requested_at = Carbon::now();
        $customer->save();

        // Send email notification to admin
        $mailer = \Botble\Base\Facades\EmailHandler::module('ecommerce');
        $data = [
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'reseller_id' => $customer->reseller_id,
            'requested_at' => $customer->reseller_deletion_requested_at->format('Y-m-d H:i:s'),
        ];
        $mailer->setVariableValues($data);
        $mailer->sendUsingTemplate('reseller-deletion-requested', get_admin_email()->first());

        return $this
            ->httpResponse()
            ->setMessage(__('Reseller account deletion requested successfully.'));
    }

    public function generateLink(Request $request, int $productId = null): BaseHttpResponse
    {
        $customer = auth('customer')->user();

        if (!$customer || !$customer->is_reseller_active) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Reseller mode is not active'));
        }

        $link = $customer->generateResellerLink($productId);

        return $this
            ->httpResponse()
            ->setData(['link' => $link]);
    }

    public function analytics(Request $request)
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $period = $request->input('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        $clicksData = ResellerClick::where('reseller_id', $customer->id)
            ->where('clicked_at', '>=', $startDate)
            ->selectRaw('DATE(clicked_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $ordersData = ResellerOrder::where('reseller_id', $customer->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(commission_earned) as earnings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = ResellerClick::where('reseller_id', $customer->id)
            ->where('clicked_at', '>=', $startDate)
            ->whereNotNull('product_id')
            ->selectRaw('product_id, COUNT(*) as clicks')
            ->groupBy('product_id')
            ->orderByDesc('clicks')
            ->limit(10)
            ->with('product')
            ->get();

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Reseller Dashboard'), route('customer.reseller.dashboard'))
            ->add(__('Analytics'), route('customer.reseller.analytics'));

        return Theme::scope('ecommerce.reseller-analytics', compact('clicksData', 'ordersData', 'topProducts', 'period'))
            ->render();
    }
}
