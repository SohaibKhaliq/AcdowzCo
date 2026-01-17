<?php

namespace Tests\Feature\API;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\PhoneOtpService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class EcommerceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh', ['--seed' => true]);
    }

    /** @test */
    public function can_send_phone_otp_via_api()
    {
        // Mock the OTP service
        $mockOtpService = Mockery::mock(PhoneOtpService::class);
        $mockOtpService->shouldReceive('sendOtp')
            ->with('+1234567890')
            ->once()
            ->andReturn(['success' => true, 'message' => 'OTP sent successfully']);

        $this->app->instance(PhoneOtpService::class, $mockOtpService);

        $response = $this->postJson(route('api.ecommerce.send-phone-otp'), [
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'message' => 'OTP sent successfully',
                ],
                'message' => 'OTP sent successfully',
            ]);
    }

    /** @test */
    public function can_verify_phone_otp_and_get_token()
    {
        $mockOtpService = Mockery::mock(PhoneOtpService::class);
        $mockOtpService->shouldReceive('verifyOtp')
            ->with('+1234567890', '123456')
            ->once()
            ->andReturn(['success' => true, 'message' => 'OTP verified successfully']);

        $this->app->instance(PhoneOtpService::class, $mockOtpService);

        $response = $this->postJson(route('api.ecommerce.verify-phone-otp'), [
            'phone' => '+1234567890',
            'code' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'customer',
                    'token',
                    'message',
                ],
                'message',
            ]);

        // Check customer was created
        $this->assertDatabaseHas('ec_customers', [
            'phone' => '+1234567890',
        ]);
    }

    /** @test */
    public function can_create_buy_now_order_with_guest_email()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 99.99,
            'status' => 'published',
        ]);

        $response = $this->postJson(route('api.ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'guest_email' => 'test@buyer.com',
            'name' => 'Test Buyer',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'order',
                    'customer',
                    'payment_url',
                ],
                'message',
            ]);

        // Check order was created
        $this->assertDatabaseHas('ec_orders', [
            'amount' => 219.98, // (99.99 * 2) + 10% tax
            'status' => 'pending',
        ]);

        // Check customer was created
        $this->assertDatabaseHas('ec_customers', [
            'email' => 'test@buyer.com',
            'name' => 'Test Buyer',
        ]);
    }

    /** @test */
    public function can_generate_referral_link()
    {
        $customer = Customer::factory()->create([
            'is_reseller_active' => true,
        ]);

        $product = Product::factory()->create([
            'slug' => 'test-product',
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson(route('api.ecommerce.generate-referral-link'), [
                'product_id' => $product->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'referral_url',
                    'referral_code',
                    'product',
                ],
                'message',
            ]);

        // Check referral code was generated
        $customer->refresh();
        $this->assertNotNull($customer->referral_code);
        $this->assertStringStartsWith('REF', $customer->referral_code);
    }

    /** @test */
    public function reseller_dashboard_returns_correct_stats()
    {
        $reseller = Customer::factory()->create([
            'is_reseller_active' => true,
        ]);

        // Create some test data
        \DB::table('ec_reseller_clicks')->insert([
            'reseller_id' => $reseller->id,
            'product_id' => 1,
            'ip_address' => '192.168.1.1',
            'clicked_at' => now()->subDays(1),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('ec_reseller_orders')->insert([
            'reseller_id' => $reseller->id,
            'product_id' => 1,
            'order_id' => 1,
            'order_amount' => 150.00,
            'commission_earned' => 7.50,
            'status' => 'approved',
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        $response = $this->actingAs($reseller, 'sanctum')
            ->getJson(route('api.ecommerce.reseller-dashboard'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
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
                ],
                'message',
            ]);

        $data = $response->json()['data'];
        $this->assertEquals(1, $data['stats']['total_clicks']);
        $this->assertEquals(1, $data['stats']['total_orders']);
        $this->assertEquals(7.50, $data['stats']['total_commission']);
    }

    /** @test */
    public function user_profile_includes_reseller_stats()
    {
        $customer = Customer::factory()->create([
            'is_reseller_active' => true,
        ]);

        // Add some reseller data
        \DB::table('ec_reseller_clicks')->insert([
            'reseller_id' => $customer->id,
            'product_id' => 1,
            'ip_address' => '192.168.1.1',
            'clicked_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('ec_reseller_orders')->insert([
            'reseller_id' => $customer->id,
            'product_id' => 1,
            'order_id' => 1,
            'order_amount' => 100.00,
            'commission_earned' => 5.00,
            'status' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($customer, 'sanctum')
            ->getJson(route('api.ecommerce.user-profile'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'is_phone_verified',
                    'has_oauth_connected',
                    'total_orders',
                    'total_spent',
                    'reseller_stats' => [
                        'total_clicks',
                        'total_commission',
                    ],
                ],
                'message',
            ]);

        $data = $response->json()['data'];
        $this->assertEquals(1, $data['reseller_stats']['total_clicks']);
        $this->assertEquals(5.00, $data['reseller_stats']['total_commission']);
    }

    /** @test */
    public function buy_now_validates_product_exists()
    {
        $response = $this->postJson(route('api.ecommerce.buy-now'), [
            'product_id' => 99999, // Non-existent product
            'quantity' => 1,
            'guest_email' => 'test@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

    /** @test */
    public function phone_otp_requires_valid_phone()
    {
        $response = $this->postJson(route('api.ecommerce.send-phone-otp'), [
            'phone' => '', // Empty phone
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);

        $response = $this->postJson(route('api.ecommerce.send-phone-otp'), [
            'phone' => 'invalid-phone',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}