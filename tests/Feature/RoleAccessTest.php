<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_provider_role_cannot_use_customer_workflows(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);
        $service = Service::create([
            'name' => 'Test Service',
            'slug' => 'test-service',
            'description' => 'Test',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($provider)
            ->get(route('customer.applications.index'))
            ->assertForbidden();

        $this->actingAs($provider)
            ->get(route('applications.create', ['service' => $service]))
            ->assertForbidden();
    }

    public function test_customer_can_use_customer_workflows_but_not_admin(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
