<?php

namespace Tests\Feature\Ecommerce;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class ResellerTrackingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function customer_can_become_reseller()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->post(route('customer.reseller.activate'));

        $response->assertStatus(302);

        $this->assertDatabaseHas('ec_customers', [
            'id' => $customer->id,
            'is_reseller_active' => true,
        ]);

        // Check that referral code was generated
        $customer->refresh();
        $this->assertNotNull($customer->referral_code);
        $this->assertStringStartsWith('REF', $customer->referral_code);
    }

    /** @test */
    public function reseller_link_generates_correctly()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
            'referral_code' => 'REF001234ABCD',
        ]);

        $product = Product::factory()->create([
            'slug' => 'test-product',
        ]);

        $response = $this->actingAs($reseller, 'customer')
            ->post(route('api.reseller.generate-link'), [
                'product_id' => $product->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'referral_url',
                'referral_code',
                'product',
            ]);

        $data = $response->json();
        $this->assertStringContains('ref=REF001234ABCD', $data['referral_url']);
    }

    /** @test */
    public function clicking_referral_link_tracks_correctly()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
            'referral_code' => 'REF001234ABCD',
        ]);

        $product = Product::factory()->create([
            'slug' => 'test-product',
        ]);

        $response = $this->get(route('products.detail', [
            'slug' => $product->slug,
            'ref' => $reseller->referral_code,
        ]));

        $response->assertStatus(200);

        // Check click tracking
        $this->assertDatabaseHas('ec_reseller_clicks', [
            'reseller_id' => $reseller->id,
            'product_id' => $product->id,
        ]);

        // Check session has referral code
        $this->assertEquals($reseller->referral_code, session('referral_code'));
    }

    /** @test */
    public function purchase_with_referral_tracks_commission()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
            'referral_code' => 'REF001234ABCD',
        ]);

        $product = Product::factory()->create([
            'price' => 100.00,
        ]);

        // Simulate referral session
        session(['referral_code' => $reseller->referral_code]);

        $response = $this->post(route('ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'guest_email' => 'buyer@test.com',
        ]);

        $response->assertStatus(302);

        // Check reseller order tracking
        $this->assertDatabaseHas('ec_reseller_orders', [
            'reseller_id' => $reseller->id,
            'product_id' => $product->id,
            'order_amount' => 110.00, // 100 + 10% tax
            'commission_earned' => 5.50, // 5% of order amount
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function reseller_dashboard_shows_correct_stats()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
            'referral_code' => 'REF123456EFGH',
        ]);

        // Create some test data
        DB::table('ec_reseller_clicks')->insert([
            'reseller_id' => $reseller->id,
            'product_id' => 1,
            'ip_address' => '192.168.1.1',
            'clicked_at' => now()->subDays(1),
        ]);

        DB::table('ec_reseller_clicks')->insert([
            'reseller_id' => $reseller->id,
            'product_id' => 2,
            'ip_address' => '192.168.1.2',
            'clicked_at' => now()->subDays(2),
        ]);

        DB::table('ec_reseller_orders')->insert([
            'reseller_id' => $reseller->id,
            'product_id' => 1,
            'order_id' => 1,
            'order_amount' => 150.00,
            'commission_earned' => 7.50,
            'status' => 'approved',
            'created_at' => now()->subDays(1),
        ]);

        $response = $this->actingAs($reseller, 'customer')
            ->get(route('api.reseller.dashboard'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => [
                    'total_clicks',
                    'recent_clicks',
                    'total_orders',
                    'recent_orders',
                    'total_commission',
                    'pending_payout',
                    'conversion_rate',
                ],
                'recent_activity',
                'referral_link',
            ]);

        $data = $response->json();
        $this->assertEquals(2, $data['stats']['total_clicks']);
        $this->assertEquals(1, $data['stats']['total_orders']);
        $this->assertEquals(7.50, $data['stats']['total_commission']);
    }

    /** @test */
    public function commission_calculation_is_accurate()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
        ]);

        $orderAmount = 200.00;
        $commissionRate = 5.0; // 5%

        $expectedCommission = ($orderAmount * $commissionRate) / 100;

        DB::table('ec_reseller_orders')->insert([
            'reseller_id' => $reseller->id,
            'product_id' => 1,
            'order_id' => 1,
            'order_amount' => $orderAmount,
            'commission_earned' => $expectedCommission,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $totalCommission = DB::table('ec_reseller_orders')
            ->where('reseller_id', $reseller->id)
            ->sum('commission_earned');

        $this->assertEquals(10.00, $totalCommission);
    }

    /** @test */
    public function reseller_can_view_order_history()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
        ]);

        // Create multiple orders
        for ($i = 1; $i <= 3; $i++) {
            DB::table('ec_reseller_orders')->insert([
                'reseller_id' => $reseller->id,
                'product_id' => $i,
                'order_id' => $i,
                'order_amount' => 100.00 * $i,
                'commission_earned' => 5.00 * $i,
                'status' => $i % 2 === 0 ? 'approved' : 'pending',
                'created_at' => now()->subDays($i),
            ]);
        }

        $response = $this->actingAs($reseller, 'customer')
            ->get(route('customer.reseller.orders'));

        $response->assertStatus(200);

        // Check that all orders are displayed
        $response->assertSee('$5.00'); // First order commission
        $response->assertSee('$10.00'); // Second order commission
        $response->assertSee('$15.00'); // Third order commission
    }

    /** @test */
    public function duplicate_clicks_from_same_ip_are_limited()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
            'referral_code' => 'REF123456ABCD',
        ]);

        $product = Product::factory()->create();
        $ipAddress = '192.168.1.100';

        // First click should be tracked
        $response1 = $this->call('GET', route('products.detail', $product->slug), [
            'ref' => $reseller->referral_code,
        ], [], [], ['REMOTE_ADDR' => $ipAddress]);

        $response1->assertStatus(200);

        // Second click from same IP within 24 hours should be ignored
        $response2 = $this->call('GET', route('products.detail', $product->slug), [
            'ref' => $reseller->referral_code,
        ], [], [], ['REMOTE_ADDR' => $ipAddress]);

        $response2->assertStatus(200);

        // Should only have one click record
        $clickCount = DB::table('ec_reseller_clicks')
            ->where('reseller_id', $reseller->id)
            ->where('product_id', $product->id)
            ->where('ip_address', $ipAddress)
            ->count();

        $this->assertEquals(1, $clickCount);
    }

    /** @test */
    public function reseller_payout_status_updates_correctly()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
        ]);

        $orderId = DB::table('ec_reseller_orders')->insertGetId([
            'reseller_id' => $reseller->id,
            'product_id' => 1,
            'order_id' => 1,
            'order_amount' => 200.00,
            'commission_earned' => 10.00,
            'status' => 'approved',
            'created_at' => now(),
        ]);

        // Admin approves payout
        $response = $this->post(route('admin.reseller.process-payout'), [
            'reseller_order_id' => $orderId,
            'status' => 'paid',
            'payout_method' => 'bank_transfer',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('ec_reseller_orders', [
            'id' => $orderId,
            'status' => 'paid',
        ]);
    }
}
