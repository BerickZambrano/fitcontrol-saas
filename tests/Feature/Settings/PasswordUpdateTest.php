<?php

namespace Tests\Feature\Settings;

use App\Livewire\Settings\Password;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        $response = Livewire::test(Password::class)
            ->set('current_password', 'password')
            ->set('password', 'New-password123!')
            ->set('password_confirmation', 'New-password123!')
            ->call('updatePassword');

        $response->assertHasNoErrors();

        $this->assertTrue(Hash::check('New-password123!', $user->refresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        $response = Livewire::test(Password::class)
            ->set('current_password', 'wrong-password')
            ->set('password', 'New-password123!')
            ->set('password_confirmation', 'New-password123!')
            ->call('updatePassword');

        $response->assertHasErrors(['current_password']);
    }
}
