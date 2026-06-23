<?php

namespace Tests\Feature;

use App\Models\Equipo;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_see_other_tenant_data()
    {
        $tenantA = Tenant::factory()->create(['nombre' => 'Tenant A']);
        $tenantB = Tenant::factory()->create(['nombre' => 'Tenant B']);

        $role = Role::create(['name' => 'Administrador', 'guard_name' => 'web']);
        $permission = \Spatie\Permission\Models\Permission::create(['name' => 'ViewAny:Equipo', 'guard_name' => 'web']);
        $userA = User::factory()->create(['tenant_id' => $tenantA->id]);
        $userA->assignRole($role);
        $userA->givePermissionTo($permission);

        $equipoB = Equipo::factory()->create(['tenant_id' => $tenantB->id, 'nombre' => 'Equipo B Secreto']);

        $this->actingAs($userA);

        // Suponiendo que hay una ruta que lista equipos
        $response = $this->get('/admin/equipos');

        $response->assertStatus(200);
        $response->assertDontSee('Equipo B Secreto');
    }

    public function test_global_tenant_scope_is_applied()
    {
        $tenantA = Tenant::factory()->create();
        $tenantB = Tenant::factory()->create();

        $userA = User::factory()->create(['tenant_id' => $tenantA->id]);
        
        Equipo::factory()->create(['tenant_id' => $tenantA->id, 'nombre' => 'Mi Equipo']);
        Equipo::factory()->create(['tenant_id' => $tenantB->id, 'nombre' => 'Otro Equipo']);

        $this->actingAs($userA);

        $this->assertEquals(1, Equipo::count());
        $this->assertEquals('Mi Equipo', Equipo::first()->nombre);
    }
}
