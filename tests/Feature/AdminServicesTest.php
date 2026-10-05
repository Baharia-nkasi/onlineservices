<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_update_a_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.services.store'), [
                'name' => 'Passport Application',
                'slug' => 'passport-application',
                'description' => 'Passport support',
                'government_fee' => 50000,
                'service_fee' => 10000,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.services.index'));

        $service = Service::where('slug', 'passport-application')->firstOrFail();

        $this->assertTrue($service->is_active);

        $this->actingAs($admin)
            ->patch(route('admin.services.update', $service), [
                'name' => 'Passport Application Updated',
                'slug' => 'passport-application-updated',
                'description' => 'Updated description',
                'government_fee' => 60000,
                'service_fee' => 15000,
                'is_active' => 0,
            ])
            ->assertRedirect();

        $service->refresh();
        $this->assertFalse($service->is_active);
        $this->assertSame('passport-application-updated', $service->slug);
    }

    public function test_customer_cannot_manage_services(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.services.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->post(route('admin.services.store'), [
                'name' => 'Forbidden Service',
                'slug' => 'forbidden-service',
                'government_fee' => 0,
                'service_fee' => 0,
                'is_active' => 1,
            ])
            ->assertForbidden();
    }

    public function test_customer_only_sees_active_services(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        Service::create([
            'name' => 'Active Service',
            'slug' => 'active-service',
            'description' => 'Visible',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Hidden Service',
            'slug' => 'hidden-service',
            'description' => 'Hidden',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => false,
        ]);

        $this->actingAs($customer)
            ->get(route('services.index'))
            ->assertOk()
            ->assertSee('Active Service')
            ->assertDontSee('Hidden Service');
    }

    public function test_only_deactivated_service_without_applications_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $service = Service::create([
            'name' => 'Old Service',
            'slug' => 'old-service',
            'description' => 'Old',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.services.destroy', $service))
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseMissing('services', ['id' => $service->id]);

        $protectedService = Service::create([
            'name' => 'Historical Service',
            'slug' => 'historical-service',
            'description' => 'Has applications',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => false,
        ]);

        Application::create([
            'user_id' => User::factory()->create(['role' => 'customer'])->id,
            'service_id' => $protectedService->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.services.destroy', $protectedService))
            ->assertRedirect()
            ->assertSessionHasErrors('service');

        $this->assertDatabaseHas('services', ['id' => $protectedService->id]);
    }


    public function test_admin_can_manage_service_document_requirements(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $service = Service::create([
            'name' => 'Identity Service',
            'slug' => 'identity-service',
            'description' => 'Identity',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.services.documents.store', $service), [
                'name' => 'National ID',
                'description' => 'Valid ID',
                'is_required' => 1,
                'requirement_type' => 'single',
                'requirement_group' => 'ignored',
                'minimum_required' => 5,
                'sort_order' => 1,
                'is_active' => 1,
            ])
            ->assertRedirect();

        $document = $service->documents()->firstOrFail();
        $this->assertNull($document->requirement_group);
        $this->assertSame(1, $document->minimum_required);

        $this->actingAs($admin)
            ->patch(route('admin.service-documents.update', $document), [
                'name' => 'National ID Updated',
                'description' => 'Updated',
                'is_required' => 1,
                'requirement_type' => 'single',
                'requirement_group' => null,
                'minimum_required' => 1,
                'sort_order' => 2,
                'is_active' => 0,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('service_documents', [
            'id' => $document->id,
            'name' => 'National ID Updated',
            'is_active' => false,
        ]);
    }

    public function test_active_service_cannot_be_deleted_until_deactivated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $service = Service::create([
            'name' => 'Active Protected Service',
            'slug' => 'active-protected-service',
            'description' => 'Active',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.services.destroy', $service))
            ->assertRedirect()
            ->assertSessionHasErrors('service');

        $this->assertDatabaseHas('services', ['id' => $service->id]);
    }
    public function test_admin_can_delete_unused_document_requirement(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $service = Service::create([
            'name' => 'Document Service',
            'slug' => 'document-service',
            'description' => 'Documents',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $document = $service->documents()->create([
            'name' => 'Old Requirement',
            'description' => 'No longer needed',
            'is_required' => true,
            'requirement_type' => 'single',
            'minimum_required' => 1,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.service-documents.destroy', $document))
            ->assertRedirect();

        $this->assertDatabaseMissing('service_documents', ['id' => $document->id]);
    }

    public function test_requirement_with_existing_applications_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $service = Service::create([
            'name' => 'Historical Document Service',
            'slug' => 'historical-document-service',
            'description' => 'Historical',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => false,
        ]);

        $document = $service->documents()->create([
            'name' => 'Historical Requirement',
            'description' => 'Keep for history',
            'is_required' => true,
            'requirement_type' => 'single',
            'minimum_required' => 1,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Application::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'completed',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.service-documents.destroy', $document))
            ->assertRedirect()
            ->assertSessionHasErrors('document');

        $this->assertDatabaseHas('service_documents', ['id' => $document->id]);
    }

}
