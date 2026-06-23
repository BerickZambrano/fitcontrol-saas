<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test visual login flow.
     */
    public function test_user_can_login_visually(): void
    {
        $tenant = \App\Models\Tenant::factory()->create();
        $user = \App\Models\User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'dusk.user@example.com',
            'password' => bcrypt('New-password123!'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]')
                ->type('email', $user->email)
                ->type('password', 'New-password123!')
                ->click('[data-test="login-button"]')
                ->waitForLocation('/jugador')
                ->assertPathIs('/jugador');
        });
    }
}
