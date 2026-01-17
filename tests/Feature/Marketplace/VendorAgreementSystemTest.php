<?php

namespace Tests\Feature\Marketplace;

use Botble\ACL\Models\User;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\SubscriptionPlan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;

class VendorAgreementSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function vendor_can_register_with_commission_agreement()
    {
        $customer = Customer::factory()->create([
            'name' => 'John Vendor',
            'email' => 'john@vendor.com',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('marketplace.vendor.become-vendor.post'), [
                'shop_name' => 'Johns Electronics',
                'shop_phone' => '1234567890',
                'shop_url' => 'johns-electronics',
                'agreement_type' => 'commission',
                'agreement_notes' => 'Looking forward to working together',
                'agree_terms_and_policy' => 1,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('mp_stores', [
            'name' => 'Johns Electronics',
            'customer_id' => $customer->id,
            'agreement_type' => 'commission',
        ]);

        $store = Store::where('customer_id', $customer->id)->first();
        $this->assertNotNull($store->agreement_accepted_at);
        $this->assertNotNull($store->agreement_history);
        $this->assertIsArray($store->agreement_history);
        $this->assertGreaterThan(0, count($store->agreement_history));
    }

    /** @test */
    public function vendor_can_register_with_flat_fee_agreement()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->post(route('marketplace.vendor.become-vendor.post'), [
                'shop_name' => 'Test Store',
                'shop_phone' => '9876543210',
                'shop_url' => 'test-store',
                'agreement_type' => 'flat_fee',
                'agreement_notes' => 'Prefer flat fee model',
                'agree_terms_and_policy' => 1,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('mp_stores', [
            'name' => 'Test Store',
            'agreement_type' => 'flat_fee',
        ]);
    }

    /** @test */
    public function admin_can_update_vendor_agreement()
    {
        $admin = User::factory()->create();

        $customer = Customer::factory()->create(['is_vendor' => true]);
        $store = Store::factory()->create([
            'customer_id' => $customer->id,
            'agreement_type' => 'commission',
            'agreement_value' => 5.0,
            'commission_rate' => 5.0,
        ]);

        $response = $this->actingAs($admin, 'web')
            ->put(route('marketplace.store.update', $store->id), [
                'name' => $store->name,
                'email' => $store->email,
                'phone' => $store->phone,
                'customer_id' => $customer->id,
                'status' => 'published',
                'agreement_type' => 'commission',
                'agreement_value' => 8.5,
                'commission_rate' => 8.5,
                'agreement_notes' => 'Increased commission rate',
            ]);

        $response->assertRedirect();

        $store->refresh();

        $this->assertEquals(8.5, $store->agreement_value);
        $this->assertEquals(8.5, $store->commission_rate);
        $this->assertNotNull($store->agreement_updated_at);
        $this->assertEquals($admin->id, $store->agreement_last_updated_by);
    }

    /** @test */
    public function agreement_history_tracks_changes()
    {
        $admin = User::factory()->create();
        $customer = Customer::factory()->create(['is_vendor' => true]);

        $store = Store::factory()->create([
            'customer_id' => $customer->id,
            'agreement_type' => 'commission',
            'agreement_value' => 5.0,
            'commission_rate' => 5.0,
            'agreement_history' => [],
        ]);

        // First update
        $store->updateAgreement([
            'agreement_type' => 'commission',
            'agreement_value' => 7.0,
            'commission_rate' => 7.0,
        ], $admin->id);

        $this->assertCount(1, $store->agreement_history);
        $this->assertEquals(5.0, $store->agreement_history[0]['old_values']['commission_rate']);
        $this->assertEquals(7.0, $store->agreement_history[0]['new_values']['commission_rate']);

        // Second update
        $store->updateAgreement([
            'agreement_type' => 'flat_fee',
            'agreement_value' => 100.0,
            'commission_rate' => 0,
        ], $admin->id);

        $store->refresh();

        $this->assertCount(2, $store->agreement_history);
        $this->assertEquals('commission', $store->agreement_history[1]['old_values']['type']);
        $this->assertEquals('flat_fee', $store->agreement_history[1]['new_values']['type']);
    }

    /** @test */
    public function store_can_calculate_commission_correctly()
    {
        $store = Store::factory()->create([
            'agreement_type' => 'commission',
            'commission_rate' => 10.0,
        ]);

        $commission = $store->calculateCommission(100.00);
        $this->assertEquals(10.00, $commission);

        $commission = $store->calculateCommission(250.50);
        $this->assertEquals(25.05, $commission);
    }

    /** @test */
    public function flat_fee_store_returns_zero_commission()
    {
        $store = Store::factory()->create([
            'agreement_type' => 'flat_fee',
            'agreement_value' => 99.99,
        ]);

        $commission = $store->calculateCommission(100.00);
        $this->assertEquals(0, $commission);
    }

    /** @test */
    public function store_with_subscription_plan_can_be_created()
    {
        $plan = SubscriptionPlan::factory()->create([
            'name' => 'Premium Plan',
            'price' => 49.99,
            'commission_rate' => 5.0,
        ]);

        $customer = Customer::factory()->create(['is_vendor' => true]);

        $store = Store::factory()->create([
            'customer_id' => $customer->id,
            'agreement_type' => 'subscription',
            'subscription_plan_id' => $plan->id,
            'commission_rate' => $plan->commission_rate,
        ]);

        $this->assertNotNull($store->subscriptionPlan);
        $this->assertEquals('Premium Plan', $store->subscriptionPlan->name);
        $this->assertEquals(5.0, $store->commission_rate);
    }

    /** @test */
    public function agreement_display_text_is_correct_for_commission()
    {
        $store = Store::factory()->create([
            'agreement_type' => 'commission',
            'commission_rate' => 7.5,
        ]);

        $displayText = $store->getAgreementDisplayText();

        $this->assertStringContainsString('7.50%', $displayText);
        $this->assertStringContainsString('Commission', $displayText);
    }

    /** @test */
    public function agreement_display_text_is_correct_for_flat_fee()
    {
        $store = Store::factory()->create([
            'agreement_type' => 'flat_fee',
            'agreement_value' => 99.99,
        ]);

        $displayText = $store->getAgreementDisplayText();

        $this->assertStringContainsString('99.99', $displayText);
        $this->assertStringContainsString('Flat Fee', $displayText);
    }

    /** @test */
    public function has_accepted_agreement_returns_correct_value()
    {
        $store1 = Store::factory()->create([
            'agreement_accepted_at' => now(),
        ]);

        $store2 = Store::factory()->create([
            'agreement_accepted_at' => null,
        ]);

        $this->assertTrue($store1->hasAcceptedAgreement());
        $this->assertFalse($store2->hasAcceptedAgreement());
    }

    /** @test */
    public function agreement_updated_by_relationship_works()
    {
        $admin = User::factory()->create(['name' => 'Admin User']);

        $store = Store::factory()->create([
            'agreement_last_updated_by' => $admin->id,
        ]);

        $this->assertInstanceOf(User::class, $store->agreementUpdatedBy);
        $this->assertEquals('Admin User', $store->agreementUpdatedBy->name);
    }

    /** @test */
    public function changing_agreement_type_from_commission_to_flat_fee()
    {
        $admin = User::factory()->create();
        $store = Store::factory()->create([
            'agreement_type' => 'commission',
            'commission_rate' => 10.0,
            'agreement_value' => 10.0,
        ]);

        $store->updateAgreement([
            'agreement_type' => 'flat_fee',
            'agreement_value' => 199.99,
            'commission_rate' => 0,
        ], $admin->id);

        $store->refresh();

        $this->assertEquals('flat_fee', $store->agreement_type);
        $this->assertEquals(199.99, $store->agreement_value);
        $this->assertEquals(0, $store->calculateCommission(1000));
    }

    /** @test */
    public function subscription_plan_id_can_be_null()
    {
        $store = Store::factory()->create([
            'agreement_type' => 'commission',
            'subscription_plan_id' => null,
        ]);

        $this->assertNull($store->subscription_plan_id);
        $this->assertNotNull($store->subscriptionPlan); // withDefault() returns empty model
    }
}
