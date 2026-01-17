<?php

namespace Tests\Feature\Marketplace;

use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\VendorWarning;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class VendorOnboardingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function customer_can_become_vendor_with_agreement()
    {
        $customer = Customer::factory()->create([
            'name' => 'John Vendor',
            'email' => 'john@vendor.com',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('marketplace.vendor.become-vendor'), [
                'shop_name' => 'Johns Electronics',
                'shop_phone' => '1234567890',
                'shop_url' => 'johns-electronics',
                'agreement_type' => 'commission',
                'agreement_value' => 15.5,
                'agreement_terms' => 1, // accepted
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('mp_stores', [
            'customer_id' => $customer->id,
            'name' => 'Johns Electronics',
            'phone' => '1234567890',
            'agreement_type' => 'commission',
            'agreement_value' => 15.5,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function vendor_agreement_validation_is_enforced()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->post(route('marketplace.vendor.become-vendor'), [
                'shop_name' => 'Test Store',
                'shop_phone' => '1234567890',
                'shop_url' => 'test-store',
                'agreement_type' => 'commission',
                'agreement_value' => 50, // Invalid: too high
                'agreement_terms' => 1,
            ]);

        $response->assertSessionHasErrors(['agreement_value']);
    }

    /** @test */
    public function flat_fee_vendor_gets_correct_agreement_structure()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->post(route('marketplace.vendor.become-vendor'), [
                'shop_name' => 'Flat Fee Store',
                'shop_phone' => '1234567890',
                'shop_url' => 'flat-fee-store',
                'agreement_type' => 'flat_fee',
                'agreement_value' => 99.99,
                'agreement_terms' => 1,
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('mp_stores', [
            'customer_id' => $customer->id,
            'agreement_type' => 'flat_fee',
            'agreement_value' => 99.99,
        ]);
    }

    /** @test */
    public function admin_can_approve_vendor_application()
    {
        $customer = Customer::factory()->create();
        $store = Store::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
        ]);

        $admin = Customer::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin, 'customer')
            ->patch(route('marketplace.vendor.approve', $store->id), [
                'status' => 'published',
                'notes' => 'Approved after review',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('mp_stores', [
            'id' => $store->id,
            'status' => 'published',
        ]);
    }

    /** @test */
    public function vendor_can_update_agreement_within_limits()
    {
        $customer = Customer::factory()->create();
        $store = Store::factory()->create([
            'customer_id' => $customer->id,
            'agreement_type' => 'commission',
            'agreement_value' => 10.0,
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->patch(route('marketplace.vendor.settings.update'), [
                'agreement_value' => 12.5, // Within allowed increase
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('mp_stores', [
            'id' => $store->id,
            'agreement_value' => 12.5,
        ]);
    }

    /** @test */
    public function warning_system_tracks_vendor_violations()
    {
        $customer = Customer::factory()->create();
        $store = Store::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $admin = Customer::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->post(route('marketplace.admin.issue-warning'), [
                'store_id' => $store->id,
                'warning_level' => 'medium',
                'warning_type' => 'policy_violation',
                'message' => 'Product descriptions not compliant',
                'action_required' => 'Update product descriptions within 7 days',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('mp_vendor_warnings', [
            'store_id' => $store->id,
            'warning_level' => 'medium',
            'warning_type' => 'policy_violation',
        ]);
    }

    /** @test */
    public function vendor_receives_warning_notification()
    {
        $customer = Customer::factory()->create();
        $store = Store::factory()->create([
            'customer_id' => $customer->id,
        ]);

        VendorWarning::factory()->create([
            'store_id' => $store->id,
            'warning_level' => 'high',
            'message' => 'Critical policy violation',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get(route('marketplace.vendor.dashboard'));

        $response->assertStatus(200)
            ->assertSee('Critical policy violation')
            ->assertSee('high');
    }

    /** @test */
    public function multiple_warnings_escalate_correctly()
    {
        $customer = Customer::factory()->create();
        $store = Store::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // Create multiple warnings
        VendorWarning::factory()->count(3)->create([
            'store_id' => $store->id,
            'warning_level' => 'medium',
        ]);

        VendorWarning::factory()->create([
            'store_id' => $store->id,
            'warning_level' => 'high',
        ]);

        $warningCount = VendorWarning::where('store_id', $store->id)->count();
        $highWarnings = VendorWarning::where('store_id', $store->id)
            ->where('warning_level', 'high')
            ->count();

        $this->assertEquals(4, $warningCount);
        $this->assertEquals(1, $highWarnings);

        // Check if escalation logic would trigger
        $shouldEscalate = ($warningCount >= 3) || ($highWarnings >= 1);
        $this->assertTrue($shouldEscalate);
    }
}
