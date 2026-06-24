<?php

namespace Tests\Feature;

use App\Filament\Resources\EquipoUsers\Pages\CreateEquipoUser;
use App\Models\Equipo;
use App\Models\EquipoUser;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EquipoUserValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_assign_player_already_in_another_team()
    {
        $tenant = Tenant::factory()->create();
        
        // Ensure roles exist
        $adminRole = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $playerRole = Role::firstOrCreate(['name' => 'Jugador', 'guard_name' => 'web']);

        $admin = User::factory()->create(['tenant_id' => $tenant->id]);
        $admin->assignRole($adminRole);
        $admin->givePermissionTo(
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'Create:EquipoUser', 'guard_name' => 'web']),
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'ViewAny:EquipoUser', 'guard_name' => 'web'])
        );

        $player = User::factory()->create(['tenant_id' => $tenant->id]);
        $player->assignRole($playerRole);

        $teamA = Equipo::factory()->create(['tenant_id' => $tenant->id]);
        $teamB = Equipo::factory()->create(['tenant_id' => $tenant->id]);

        // Assign player to team A (active assignment)
        EquipoUser::create([
            'tenant_id' => $tenant->id,
            'equipo_id' => $teamA->id,
            'user_id' => $player->id,
            'fecha_inicio' => now()->subDays(5)->toDateString(),
            'fecha_fin' => null,
        ]);

        $this->actingAs($admin);
        $this->withoutExceptionHandling();

        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));
        \Filament\Facades\Filament::setTenant($tenant);

        // Try to assign the player to team B via Filament's form
        Livewire::test(CreateEquipoUser::class)
            ->fillForm([
                'equipo_id' => $teamB->id,
                'user_id' => $player->id,
                'fecha_inicio' => now()->toDateString(),
            ])
            ->call('create')
            ->assertHasErrors(['data.user_id']);
    }

    public function test_can_assign_player_if_previous_team_assignment_expired()
    {
        $tenant = Tenant::factory()->create();
        
        $adminRole = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $playerRole = Role::firstOrCreate(['name' => 'Jugador', 'guard_name' => 'web']);

        $admin = User::factory()->create(['tenant_id' => $tenant->id]);
        $admin->assignRole($adminRole);
        $admin->givePermissionTo(
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'Create:EquipoUser', 'guard_name' => 'web']),
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'ViewAny:EquipoUser', 'guard_name' => 'web'])
        );

        $player = User::factory()->create(['tenant_id' => $tenant->id]);
        $player->assignRole($playerRole);

        $teamA = Equipo::factory()->create(['tenant_id' => $tenant->id]);
        $teamB = Equipo::factory()->create(['tenant_id' => $tenant->id]);

        // Assign player to team A (expired assignment)
        EquipoUser::create([
            'tenant_id' => $tenant->id,
            'equipo_id' => $teamA->id,
            'user_id' => $player->id,
            'fecha_inicio' => now()->subDays(10)->toDateString(),
            'fecha_fin' => now()->subDay()->toDateString(),
        ]);

        $this->actingAs($admin);

        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));
        \Filament\Facades\Filament::setTenant($tenant);

        // Try to assign the player to team B via Filament's form (should succeed)
        Livewire::test(CreateEquipoUser::class)
            ->fillForm([
                'equipo_id' => $teamB->id,
                'user_id' => $player->id,
                'fecha_inicio' => now()->toDateString(),
            ])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertTrue(
            EquipoUser::where('user_id', $player->id)
                ->where('equipo_id', $teamB->id)
                ->exists()
        );
    }
}
