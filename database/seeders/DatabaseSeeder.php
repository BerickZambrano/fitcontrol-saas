<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Orden respetando llaves foráneas
        $this->call([
            TenantSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            EquipoSeeder::class,
            TorneoSeeder::class,
            JugadorPerfilSeeder::class,
            EquipoUserSeeder::class,
            EntrenamientoSeeder::class,
            PartidoSeeder::class,
            AsistenciaEntrenamientoSeeder::class,
            PagoSeeder::class,
            HistorialMedicoSeeder::class,
            RendimientoSeeder::class,
        ]);

        // Crear usuarios administradores DESPUÉS de tenants
        User::create([
            'name' => 'Administrador',
            'email' => 'administrador@fitcontrol.com',
            'password' => Hash::make('password123'),
            'tenant_id' => 1,
        ]);
        
        User::create([
            'name' => 'Berick',
            'email' => 'administradordos@fitcontrol.com',
            'password' => Hash::make('16112007'),
            'tenant_id' => 2,
        ]);
    }
}
