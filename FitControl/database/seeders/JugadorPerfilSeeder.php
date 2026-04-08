<?php

namespace Database\Seeders;

use App\Models\JugadorPerfil;
use Illuminate\Database\Seeder;

class JugadorPerfilSeeder extends Seeder
{
    public function run(): void
    {
        // UserSeeder crea 10 users con IDs 1-10
        // Admins se crean después con IDs 11-12
        $jugadores = [
            // Tenant 1 - Users 1, 2, 3, 10
            ['tenant_id' => 1, 'user_id' => 1, 'posicion' => 'Delantero', 'dorsal' => 9, 'altura' => 1.82, 'peso' => 78.5, 'pierna_habil' => 'derecha'],
            ['tenant_id' => 1, 'user_id' => 2, 'posicion' => 'Mediocampista', 'dorsal' => 8, 'altura' => 1.75, 'peso' => 72.0, 'pierna_habil' => 'izquierda'],
            ['tenant_id' => 1, 'user_id' => 3, 'posicion' => 'Defensa', 'dorsal' => 4, 'altura' => 1.85, 'peso' => 80.0, 'pierna_habil' => 'derecha'],
            ['tenant_id' => 1, 'user_id' => 10, 'posicion' => 'Portero', 'dorsal' => 1, 'altura' => 1.90, 'peso' => 85.0, 'pierna_habil' => 'derecha'],
            // Tenant 2 - Users 4, 5, 6
            ['tenant_id' => 2, 'user_id' => 4, 'posicion' => 'Delantero', 'dorsal' => 11, 'altura' => 1.78, 'peso' => 75.0, 'pierna_habil' => 'ambas'],
            ['tenant_id' => 2, 'user_id' => 5, 'posicion' => 'Mediocampista', 'dorsal' => 6, 'altura' => 1.72, 'peso' => 70.0, 'pierna_habil' => 'izquierda'],
            ['tenant_id' => 2, 'user_id' => 6, 'posicion' => 'Defensa', 'dorsal' => 3, 'altura' => 1.83, 'peso' => 79.0, 'pierna_habil' => 'derecha'],
            // Tenant 3 - Users 7, 8, 9
            ['tenant_id' => 3, 'user_id' => 7, 'posicion' => 'Delantero', 'dorsal' => 7, 'altura' => 1.80, 'peso' => 76.5, 'pierna_habil' => 'derecha'],
            ['tenant_id' => 3, 'user_id' => 8, 'posicion' => 'Mediocampista', 'dorsal' => 10, 'altura' => 1.76, 'peso' => 73.0, 'pierna_habil' => 'ambas'],
            ['tenant_id' => 3, 'user_id' => 9, 'posicion' => 'Portero', 'dorsal' => 13, 'altura' => 1.88, 'peso' => 83.0, 'pierna_habil' => 'derecha'],
        ];

        foreach ($jugadores as $jugador) {
            JugadorPerfil::create($jugador);
        }
    }
}
