<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_protected_routes_redirect_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_api_routes_require_authentication()
    {
        $response = $this->postJson('/api/v1/mail/send-single', [
            'email' => 'test@example.com',
            'nombre' => 'Test',
            'subject' => 'Sub',
            'body' => 'Body'
        ]);

        $response->assertStatus(401);
    }
}
