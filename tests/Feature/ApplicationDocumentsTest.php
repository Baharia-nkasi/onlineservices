<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationDocumentsTest extends TestCase
{
    use RefreshDatabase;

    private function applicationFor(User $user): Application
    {
        $service = Service::create([
            'name' => 'Test Service',
            'slug' => 'test-service-'.uniqid(),
            'description' => 'Test service',
            'government_fee' => 0,
            'service_fee' => 0,
            'is_active' => true,
        ]);

        return Application::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'status' => 'pending',
        ]);
    }

    private function documentFor(Application $application): ApplicationDocument
    {
        return ApplicationDocument::create([
            'application_id' => $application->id,
            'document_name' => 'National ID',
            'file_name' => 'national-id.pdf',
            'file_path' => 'database://application-documents/test',
            'file_content' => '%PDF-test-content%',
            'file_type' => 'application/pdf',
            'file_size' => 18,
            'status' => 'pending',
        ]);
    }

    public function test_customer_can_view_and_download_own_document(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $application = $this->applicationFor($customer);
        $document = $this->documentFor($application);

        $this->actingAs($customer)
            ->get(route('application.documents.view', $document))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'inline; filename="national-id.pdf"')
            ->assertSee('%PDF-test-content%', false);

        $this->actingAs($customer)
            ->get(route('application.documents.download', $document))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="national-id.pdf"')
            ->assertSee('%PDF-test-content%', false);
    }

    public function test_customer_cannot_view_another_customers_document(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $otherCustomer = User::factory()->create(['role' => 'customer']);
        $document = $this->documentFor($this->applicationFor($owner));

        $this->actingAs($otherCustomer)
            ->get(route('application.documents.view', $document))
            ->assertForbidden();

        $this->actingAs($otherCustomer)
            ->get(route('application.documents.download', $document))
            ->assertForbidden();
    }

    public function test_admin_can_view_and_download_customer_document(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);
        $document = $this->documentFor($this->applicationFor($customer));

        $this->actingAs($admin)
            ->get(route('application.documents.view', $document))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'inline; filename="national-id.pdf"');

        $this->actingAs($admin)
            ->get(route('application.documents.download', $document))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="national-id.pdf"');
    }

    public function test_customer_my_applications_contains_only_their_applications(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $otherCustomer = User::factory()->create(['role' => 'customer']);

        $ownApplication = $this->applicationFor($customer);
        $otherApplication = $this->applicationFor($otherCustomer);

        $response = $this->actingAs($customer)->get(route('customer.applications.index'));

        $response->assertOk()
            ->assertSee('Test Service')
            ->assertSee((string) $ownApplication->id)
            ->assertDontSee((string) $otherApplication->id);
    }

    public function test_customer_cannot_open_another_customers_application(): void
    {
        $owner = User::factory()->create(['role' => 'customer']);
        $otherCustomer = User::factory()->create(['role' => 'customer']);
        $application = $this->applicationFor($owner);

        $this->actingAs($otherCustomer)
            ->get(route('customer.applications.show', $application))
            ->assertForbidden();
    }
}
