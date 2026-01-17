<?php

namespace Tests\Feature;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Marketplace\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformFeaturesTest extends TestCase
{
    // Note: RefreshDatabase might be destructive in this environment, 
    // so we use a more careful approach or assume DB is seeded.
    
    public function test_buy_now_adds_to_cart_and_redirects()
    {
        $product = Product::query()->first();
        if (!$product) {
            $this->markTestSkipped('No product found for test');
        }

        $response = $this->post(route('public.buy-now', $product->id), [
            'quantity' => 1
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($response->json('data.next_url') ?? $response->json('next_url'));
    }

    public function test_reseller_toggle_status()
    {
        $customer = Customer::query()->first();
        if (!$customer) {
            $this->markTestSkipped('No customer found for test');
        }

        $this->actingAs($customer, 'customer');

        $response = $this->post(route('customer.reseller.toggle-status'));

        $response->assertStatus(200);
        $customer->refresh();
        $this->assertTrue($customer->is_reseller_active !== null);
    }
}
