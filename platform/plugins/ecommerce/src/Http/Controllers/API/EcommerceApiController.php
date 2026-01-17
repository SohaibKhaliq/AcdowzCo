<?php

namespace Botble\Ecommerce\Http\Controllers\API;

use Botble\Api\Http\Controllers\BaseApiController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Services\PhoneOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;

class EcommerceApiController extends BaseApiController
{
    protected $phoneOtpService;

    public function __construct(PhoneOtpService $phoneOtpService)
    {
        $this->phoneOtpService = $phoneOtpService;
    }

    public function sendPhoneOtp(Request $request): BaseHttpResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        try {
            $result = $this->phoneOtpService->sendOtp($request->phone);

            return $this->httpResponse()
                ->setData(['message' => $result['message']])
                ->setMessage('OTP sent successfully');
        } catch (\Exception $e) {
            return $this->httpResponse()
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function verifyPhoneOtp(Request $request): BaseHttpResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'code' => 'required|string|size:6',
        ]);

        try {
            $result = $this->phoneOtpService->verifyOtp($request->phone, $request->code);

            if ($result['success']) {
                // Find or create customer
                $customer = Customer::where('phone', $request->phone)->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'name' => 'Phone User',
                        'phone' => $request->phone,
                        'phone_verified_at' => now(),
                    ]);
                } else {
                    $customer->update(['phone_verified_at' => now()]);
                }

                // Generate token for API authentication
                $token = $customer->createToken('mobile-app')->plainTextToken;

                return $this->httpResponse()
                    ->setData([
                        'customer' => $customer,
                        'token' => $token,
                        'message' => $result['message']
                    ])
                    ->setMessage('Phone verified successfully');
            }

            return $this->httpResponse()
                ->setError()
                ->setMessage($result['message']);
        } catch (\Exception $e) {
            return $this->httpResponse()
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function buyNow(Request $request): BaseHttpResponse
    {
        $request->validate([
            'product_id' => 'required|exists:ec_products,id',
            'quantity' => 'required|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'guest_email' => 'nullable|email|required_without:phone',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Calculate total
        $subtotal = $product->price * $request->quantity;
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        // Create or find customer
        $customer = null;
        if ($request->phone) {
            $customer = Customer::where('phone', $request->phone)->first();
        } elseif ($request->guest_email) {
            $customer = Customer::where('email', $request->guest_email)->first();
        }

        if (!$customer && ($request->phone || $request->guest_email)) {
            $customer = Customer::create([
                'name' => $request->name ?? 'Guest Customer',
                'email' => $request->guest_email,
                'phone' => $request->phone,
            ]);
        }

        // Create order
        $order = Order::create([
            'user_id' => $customer?->id,
            'status' => 'pending',
            'amount' => $total,
            'currency_id' => 1,
            'payment_id' => null,
            'shipping_method' => 'default',
            'shipping_option' => json_encode([]),
            'coupon_code' => null,
            'discount_amount' => 0,
            'sub_total' => $subtotal,
            'tax_amount' => $tax,
            'shipping_amount' => 0,
            'description' => "Buy Now: {$product->name}",
            'coupon_code' => null,
            'is_confirmed' => false,
        ]);

        // Add product to order
        $order->products()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_image' => $product->image,
            'qty' => $request->quantity,
            'price' => $product->price,
            'product_options' => json_encode([]),
            'product_type' => 'physical',
        ]);

        return $this->httpResponse()
            ->setData([
                'order' => $order->load('products'),
                'customer' => $customer,
                'payment_url' => route('payments.checkout', $order->id),
            ])
            ->setMessage('Order created successfully');
    }

    public function getResellerDashboard(Request $request): BaseHttpResponse
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer || !$customer->is_reseller_active) {
            return $this->httpResponse()
                ->setError()
                ->setMessage('Not authorized as reseller');
        }

        $period = $request->input('period', 30);

        $stats = [
            'total_clicks' => DB::table('ec_reseller_clicks')
                ->where('reseller_id', $customer->id)
                ->count(),
            'recent_clicks' => DB::table('ec_reseller_clicks')
                ->where('reseller_id', $customer->id)
                ->where('clicked_at', '>=', now()->subDays($period))
                ->count(),
            'total_orders' => DB::table('ec_reseller_orders')
                ->where('reseller_id', $customer->id)
                ->count(),
            'recent_orders' => DB::table('ec_reseller_orders')
                ->where('reseller_id', $customer->id)
                ->where('created_at', '>=', now()->subDays($period))
                ->count(),
            'total_commission' => DB::table('ec_reseller_orders')
                ->where('reseller_id', $customer->id)
                ->sum('commission_earned'),
            'pending_payout' => DB::table('ec_reseller_orders')
                ->where('reseller_id', $customer->id)
                ->where('status', 'approved')
                ->sum('commission_earned'),
            'conversion_rate' => $this->calculateConversionRate($customer->id, $period),
        ];

        $recentActivity = DB::table('ec_reseller_clicks')
            ->join('ec_products', 'ec_reseller_clicks.product_id', '=', 'ec_products.id')
            ->where('ec_reseller_clicks.reseller_id', $customer->id)
            ->where('ec_reseller_clicks.clicked_at', '>=', now()->subDays(7))
            ->select(
                'ec_products.name as product_name',
                'ec_reseller_clicks.clicked_at',
                'ec_reseller_clicks.ip_address'
            )
            ->orderBy('ec_reseller_clicks.clicked_at', 'desc')
            ->limit(10)
            ->get();

        return $this->httpResponse()
            ->setData([
                'stats' => $stats,
                'recent_activity' => $recentActivity,
                'referral_link' => $customer->referral_code ? route('customer.referral', $customer->referral_code) : null,
            ])
            ->setMessage('Reseller dashboard data retrieved successfully');
    }

    public function generateReferralLink(Request $request): BaseHttpResponse
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return $this->httpResponse()
                ->setError()
                ->setMessage('Authentication required');
        }

        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        if (!$customer->referral_code) {
            $customer->update([
                'referral_code' => 'REF' . str_pad($customer->id, 6, '0', STR_PAD_LEFT) . strtoupper(substr(md5($customer->email), 0, 4))
            ]);
        }

        $referralUrl = route('products.detail', $product->slug) . '?ref=' . $customer->referral_code;

        return $this->httpResponse()
            ->setData([
                'referral_url' => $referralUrl,
                'referral_code' => $customer->referral_code,
                'product' => $product->only(['id', 'name', 'slug', 'price', 'image']),
            ])
            ->setMessage('Referral link generated successfully');
    }

    public function getUserProfile(Request $request): BaseHttpResponse
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return $this->httpResponse()
                ->setError()
                ->setMessage('Authentication required');
        }

        $profileData = $customer->toArray();
        $profileData['is_phone_verified'] = !is_null($customer->phone_verified_at);
        $profileData['has_oauth_connected'] = !is_null($customer->oauth_provider);
        $profileData['total_orders'] = $customer->orders()->count();
        $profileData['total_spent'] = $customer->orders()->where('status', 'completed')->sum('amount');

        if ($customer->is_reseller_active) {
            $profileData['reseller_stats'] = [
                'total_clicks' => DB::table('ec_reseller_clicks')
                    ->where('reseller_id', $customer->id)
                    ->count(),
                'total_commission' => DB::table('ec_reseller_orders')
                    ->where('reseller_id', $customer->id)
                    ->sum('commission_earned'),
            ];
        }

        return $this->httpResponse()
            ->setData($profileData)
            ->setMessage('Profile retrieved successfully');
    }

    private function calculateConversionRate($resellerId, $period = 30): float
    {
        $totalClicks = DB::table('ec_reseller_clicks')
            ->where('reseller_id', $resellerId)
            ->where('clicked_at', '>=', now()->subDays($period))
            ->count();

        if ($totalClicks === 0) {
            return 0;
        }

        $totalOrders = DB::table('ec_reseller_orders')
            ->where('reseller_id', $resellerId)
            ->where('created_at', '>=', now()->subDays($period))
            ->count();

        return round(($totalOrders / $totalClicks) * 100, 2);
    }
}
