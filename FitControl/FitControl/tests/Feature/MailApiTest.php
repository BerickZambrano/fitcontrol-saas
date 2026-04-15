<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class MailApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_dispatch_email_job()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        Sanctum::actingAs($user);

        \Illuminate\Support\Facades\Bus::fake();

        $response = $this->postJson('/api/v1/mail/send-single', [
            'email' => 'recipient@example.com',
            'nombre' => 'Recipient',
            'subject' => 'Hello',
            'body' => 'World'
        ]);

        $response->assertStatus(200);
        \Illuminate\Support\Facades\Bus::assertDispatched(\App\Jobs\SendBulkEmail::class);
    }
}
