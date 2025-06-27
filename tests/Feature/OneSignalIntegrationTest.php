<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WebsiteDetail;
use App\Services\OneSignalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OneSignalIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_onesignal_notification_sent_on_form_submission()
    {
        // Mock the OneSignal API response
        Http::fake([
            'onesignal.com/api/v1/notifications' => Http::response([
                'id' => 'test-notification-id',
                'recipients' => 1,
                'success' => true
            ], 200)
        ]);

        // Submit all form steps
        $this->post('/form/step1', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'company_name' => 'Acme Corp',
        ]);

        $this->post('/form/step2', [
            'website_type' => 'ecommerce',
        ]);

        $this->post('/form/step3', [
            'platform' => 'shopify',
        ]);

        // Submit the form
        $response = $this->post('/form/submit');

        // Assert the form was submitted successfully
        $response->assertRedirect('/form');
        $response->assertSessionHas('success');

        // Assert the success message includes notification info
        $this->assertStringContainsString('Notification sent to team', session('success'));

        // Verify user was created
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'company_name' => 'Acme Corp',
        ]);

        // Verify website detail was created
        $this->assertDatabaseHas('website_details', [
            'type' => 'ecommerce',
            'name' => 'shopify',
        ]);
    }

    public function test_onesignal_api_error_handled_gracefully()
    {
        // Mock OneSignal API error response
        Http::fake([
            'onesignal.com/api/v1/notifications' => Http::response([
                'errors' => ['Invalid API key']
            ], 400)
        ]);

        // Submit all form steps
        $this->post('/form/step1', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'company_name' => 'Test Corp',
        ]);

        $this->post('/form/step2', [
            'website_type' => 'blog',
        ]);

        $this->post('/form/step3', [
            'platform' => 'wordpress',
        ]);

        // Submit the form
        $response = $this->post('/form/submit');

        // Assert the form was still submitted successfully despite notification error
        $response->assertRedirect('/form');
        $response->assertSessionHas('success');

        // Verify user was still created
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'company_name' => 'Test Corp',
        ]);
    }

    public function test_onesignal_service_sends_notification_to_all_users()
    {
        $service = new OneSignalService();

        Http::fake([
            'onesignal.com/api/v1/notifications' => Http::response([
                'id' => 'test-id',
                'recipients' => 5,
                'success' => true
            ], 200)
        ]);

        $result = $service->sendNotificationToAll('Test message', ['key' => 'value']);

        $this->assertTrue($result['success']);
        $this->assertEquals('Notification sent successfully', $result['message']);
        $this->assertArrayHasKey('id', $result['data']);
    }

    public function test_onesignal_service_handles_network_errors()
    {
        $service = new OneSignalService();

        Http::fake([
            'onesignal.com/api/v1/notifications' => Http::response('', 500)
        ]);

        $result = $service->sendNotificationToAll('Test message');

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }
}
