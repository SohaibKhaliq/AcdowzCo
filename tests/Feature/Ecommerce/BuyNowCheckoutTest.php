<?php

namespace Tests\Feature\Ecommerce;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Services\PhoneOtpService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

class BuyNowCheckoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function guest_can_buy_now_with_email()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 99.99,
            'status' => 'published',
        ]);

        $response = $this->post(route('ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'guest_email' => 'guest@example.com',
            'name' => 'Guest Customer',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('ec_orders', [
            'amount' => 219.98, // (99.99 * 2) + tax
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('ec_customers', [
            'email' => 'guest@example.com',
            'name' => 'Guest Customer',
        ]);
    }

    /** @test */
    public function buy_now_with_phone_otp_verification()
    {
        // Mock the OTP service
        $mockOtpService = Mockery::mock(PhoneOtpService::class);
        $mockOtpService->shouldReceive('sendOtp')
            ->with('+1234567890')
            ->once()
            ->andReturn(['success' => true, 'message' => 'OTP sent']);

        $mockOtpService->shouldReceive('verifyOtp')
            ->with('+1234567890', '123456')
            ->once()
            ->andReturn(['success' => true, 'message' => 'OTP verified']);

        $this->app->instance(PhoneOtpService::class, $mockOtpService);

        $product = Product::factory()->create([
            'price' => 49.99,
        ]);

        // Step 1: Send OTP
        $response = $this->post(route('ecommerce.send-phone-otp'), [
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Step 2: Verify OTP and buy now
        $response = $this->post(route('ecommerce.verify-phone-otp'), [
            'phone' => '+1234567890',
            'code' => '123456',
        ]);

        $response->assertStatus(200);

        // Step 3: Buy now with verified phone
        $response = $this->post(route('ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('ec_customers', [
            'phone' => '+1234567890',
        ]);

        $this->assertDatabaseHas('ec_orders', [
            'amount' => 54.99, // 49.99 + 10% tax
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function authenticated_customer_can_buy_now_instantly()
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create([
            'price' => 199.99,
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('ecommerce.buy-now'), [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('ec_orders', [
            'user_id' => $customer->id,
            'amount' => 219.99, // 199.99 + 10% tax
        ]);
    }

    /** @test */
    public function buy_now_handles_product_variations()
    {
        $product = Product::factory()->create([
            'price' => 79.99,
            'with_storehouse_management' => true,
        ]);

        $response = $this->post(route('ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 3,
            'guest_email' => 'test@example.com',
            'product_options' => json_encode([
                'color' => 'Red',
                'size' => 'Large',
            ]),
        ]);

        $response->assertStatus(302);

        $order = Order::where('amount', 263.97)->first(); // (79.99 * 3) + tax
        $this->assertNotNull($order);

        $orderProduct = $order->products()->first();
        $this->assertNotNull($orderProduct);
        $this->assertEquals(3, $orderProduct->qty);
    }

    /** @test */
    public function buy_now_redirects_to_payment()
    {
        $product = Product::factory()->create([
            'price' => 25.00,
        ]);

        $response = $this->post(route('ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'guest_email' => 'payment@test.com',
        ]);

        $response->assertStatus(302);

        $order = Order::latest()->first();
        $this->assertNotNull($order);

        $response->assertRedirectContains('payments/checkout/' . $order->id);
    }

    /** @test */
    public function buy_now_validates_product_availability()
    {
        $product = Product::factory()->create([
            'status' => 'draft', // Not available
            'price' => 99.99,
        ]);

        $response = $this->post(route('ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'guest_email' => 'test@example.com',
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('ec_orders', [
            'amount' => 109.99,
        ]);
    }

    /** @test */
    public function buy_now_calculates_tax_correctly()
    {
        $product = Product::factory()->create([
            'price' => 100.00,
        ]);

        $response = $this->post(route('ecommerce.buy-now'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'guest_email' => 'tax@test.com',
        ]);

        $response->assertStatus(302);

        $order = Order::latest()->first();
        $this->assertEquals(200.00, $order->sub_total);
        $this->assertEquals(20.00, $order->tax_amount); // 10% tax
        $this->assertEquals(220.00, $order->amount);
    }

    /** @test */
    public function buy_now_tracks_referral_code()
    {
        $referrer = Customer::factory()->create([
            'referral_code' => 'REF123456',
        ]);

        $product = Product::factory()->create([
            'price' => 50.00,
        ]);

        $response = $this->withSession(['referral_code' => 'REF123456'])
            ->post(route('ecommerce.buy-now'), [
                'product_id' => $product->id,
                'quantity' => 1,
                'guest_email' => 'referred@test.com',
            ]);

        $response->assertStatus(302);

        // Check if reseller click was tracked
        $this->assertDatabaseHas('ec_reseller_clicks', [
            'reseller_id' => $referrer->id,
            'product_id' => $product->id,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
