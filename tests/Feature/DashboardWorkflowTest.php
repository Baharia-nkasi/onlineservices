<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_only_shows_actionable_applications(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $service = Service::create([
            'name' => 'Test Service',
            'slug' => 'test-service',
            'description' => 'Test',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $pending = Application::create(['user_id' => $customer->id, 'service_id' => $service->id, 'status' => 'pending']);
        $processing = Application::create(['user_id' => $customer->id, 'service_id' => $service->id, 'status' => 'processing']);
        $completed = Application::create(['user_id' => $customer->id, 'service_id' => $service->id, 'status' => 'completed']);
        $rejected = Application::create(['user_id' => $customer->id, 'service_id' => $service->id, 'status' => 'rejected']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('#'.$pending->id)
            ->assertSee('#'.$processing->id)
            ->assertDontSee('#'.$completed->id)
            ->assertDontSee('#'.$rejected->id);

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['status' => 'completed']))
            ->assertSessionHasErrors('status');
    }

    public function test_customer_sees_own_completion_and_document_review_updates(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $otherCustomer = User::factory()->create(['role' => 'customer']);

        $service = Service::create([
            'name' => 'Completed Service',
            'slug' => 'completed-service',
            'description' => 'Test',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $service->documents()->create([
            'name' => 'National ID',
            'description' => 'Identity document',
            'is_required' => true,
            'requirement_type' => 'single',
            'minimum_required' => 1,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $completed = Application::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'completed',
        ]);

        $approvedDocument = ApplicationDocument::create([
            'application_id' => $completed->id,
            'document_name' => 'National ID',
            'file_name' => 'id.pdf',
            'file_path' => 'database://test-placeholder',
            'file_content' => null,
            'file_type' => 'application/pdf',
            'file_size' => 100,
            'status' => 'approved',
            'notes' => 'Verified successfully.',
        ]);

        $rejected = Application::create([
            'user_id' => $otherCustomer->id,
            'service_id' => $service->id,
            'status' => 'rejected',
        ]);

        ApplicationDocument::create([
            'application_id' => $rejected->id,
            'document_name' => 'Private Document',
            'file_name' => 'private.pdf',
            'file_path' => 'database://test-placeholder',
            'file_content' => null,
            'file_type' => 'application/pdf',
            'file_size' => 100,
            'status' => 'rejected',
            'notes' => 'Private review note.',
        ]);

        $this->actingAs($customer)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Your application has been completed')
            ->assertSee('Important service documents')
            ->assertSee('National ID')
            ->assertSee('Document approved')
            ->assertSee('Verified successfully.')
            ->assertDontSee('Private Document')
            ->assertDontSee('Private review note.');

        $this->assertNotSame($completed->user_id, $rejected->user_id);
        $this->assertNotNull($approvedDocument->id);
    }

    public function test_admin_application_detail_uses_historical_requirements_and_rejects_unavailable_completion_files(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $service = Service::create([
            'name' => 'Historical Review Service',
            'slug' => 'historical-review-service',
            'description' => 'Test',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $required = $service->documents()->create([
            'name' => 'National ID',
            'description' => 'Identity',
            'is_required' => true,
            'requirement_type' => 'single',
            'minimum_required' => 1,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $application = Application::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'processing',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.applications.show', $application))
            ->assertOk()
            ->assertSee('National ID');

        $required->update(['is_active' => false]);

        $application->refresh();

        $this->actingAs($admin)
            ->get(route('admin.applications.show', $application))
            ->assertOk()
            ->assertSee('National ID');
    }

    public function test_admin_cannot_change_documents_while_application_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        $service = Service::create([
            'name' => 'Rejected Lock Service',
            'slug' => 'rejected-lock-service',
            'description' => 'Test',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        $application = Application::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'rejected',
        ]);

        $document = ApplicationDocument::create([
            'application_id' => $application->id,
            'document_name' => 'National ID',
            'file_name' => 'id.pdf',
            'file_path' => 'database://test-placeholder',
            'file_content' => null,
            'file_type' => 'application/pdf',
            'file_size' => 100,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.documents.status', $document), [
                'status' => 'approved',
                'notes' => 'Should not change',
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame('pending', $document->fresh()->status);
    }

}
