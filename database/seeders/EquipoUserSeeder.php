<?php

namespace Database\Seeders;

use App\Models\EquipoUser;
use Illuminate\Database\Seeder;

class EquipoUserSeeder extends Seeder
{
    public function run(): void
    {
        $equipoUsers = [
            // Tenant 1 - Equipo 1 con users 1, 2
            ['tenant_id' => 1, 'equipo_id' => 1, 'user_id' => 1, 'fecha_inicio' => '2026-01-15', 'fecha_fin' => null],
            ['tenant_id' => 1, 'equipo_id' => 1, 'user_id' => 2, 'fecha_inicio' => '2026-01-15', 'fecha_fin' => null],
            // Tenant 1 - Equipo 2 con users 3, 10
            ['tenant_id' => 1, 'equipo_id' => 2, 'user_id' => 3, 'fecha_inicio' => '2026-02-01', 'fecha_fin' => null],
            ['tenant_id' => 1, 'equipo_id' => 2, 'user_id' => 10, 'fecha_inicio' => '2026-02-01', 'fecha_fin' => null],
            // Tenant 2 - Equipo 5 con users 4, 5
            ['tenant_id' => 2, 'equipo_id' => 5, 'user_id' => 4, 'fecha_inicio' => '2026-02-15', 'fecha_fin' => null],
            ['tenant_id' => 2, 'equipo_id' => 5, 'user_id' => 5, 'fecha_inicio' => '2026-02-15', 'fecha_fin' => null],
            // Tenant 2 - Equipo 6 con user 6
            ['tenant_id' => 2, 'equipo_id' => 6, 'user_id' => 6, 'fecha_inicio' => '2026-03-01', 'fecha_fin' => null],
            // Tenant 3 - Equipo 8 con users 7, 8
            ['tenant_id' => 3, 'equipo_id' => 8, 'user_id' => 7, 'fecha_inicio' => '2026-01-20', 'fecha_fin' => null],
            ['tenant_id' => 3, 'equipo_id' => 8, 'user_id' => 8, 'fecha_inicio' => '2026-01-20', 'fecha_fin' => null],
            // Tenant 3 - Equipo 9 con user 9
            ['tenant_id' => 3, 'equipo_id' => 9, 'user_id' => 9, 'fecha_inicio' => '2026-02-10', 'fecha_fin' => null],
        ];

        foreach ($equipoUsers as $equipoUser) {
            EquipoUser::create($equipoUser);
        }
    }
}
