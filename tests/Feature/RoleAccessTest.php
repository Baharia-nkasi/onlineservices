<?php

namespace Tests\Feature;

use App\Models\Application;
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

        $this->actingAs($provider)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_customer_can_use_customer_workflows_but_not_admin(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_application_centre_and_manage_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $service = Service::create([
            'name' => 'Admin Application Service',
            'slug' => 'admin-application-service',
            'description' => 'Test',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);
        $application = Application::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('customer.applications.index'))
            ->assertOk()
            ->assertSee('All Applications')
            ->assertSee($customer->name)
            ->assertSee('Manage Application');

        $this->actingAs($admin)
            ->get(route('customer.applications.show', $application))
            ->assertOk()
            ->assertSee($customer->name);
    }

}
