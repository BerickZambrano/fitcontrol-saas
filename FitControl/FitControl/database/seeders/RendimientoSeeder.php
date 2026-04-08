<?php

namespace Database\Seeders;

use App\Models\Rendimiento;
use Illuminate\Database\Seeder;

class RendimientoSeeder extends Seeder
{
    public function run(): void
    {
        $rendimientos = [
            // Partido 1 (Tenant 1) - User 1 y 2 jugaron
            ['tenant_id' => 1, 'user_id' => 1, 'partido_id' => 1, 'minutos_jugados' => 90, 'goles' => 2, 'asistencias' => 1, 'tarjetas_amarillas' => 0, 'tarjetas_rojas' => 0],
            ['tenant_id' => 1, 'user_id' => 2, 'partido_id' => 1, 'minutos_jugados' => 75, 'goles' => 1, 'asistencias' => 2, 'tarjetas_amarillas' => 1, 'tarjetas_rojas' => 0],
            // Partido 2 (Tenant 1) - User 1 y 2
            ['tenant_id' => 1, 'user_id' => 1, 'partido_id' => 2, 'minutos_jugados' => 85, 'goles' => 1, 'asistencias' => 0, 'tarjetas_amarillas' => 0, 'tarjetas_rojas' => 0],
            ['tenant_id' => 1, 'user_id' => 2, 'partido_id' => 2, 'minutos_jugados' => 90, 'goles' => 0, 'asistencias' => 1, 'tarjetas_amarillas' => 0, 'tarjetas_rojas' => 0],
            // Partido 5 (Tenant 2) - User 4 y 5
            ['tenant_id' => 2, 'user_id' => 4, 'partido_id' => 5, 'minutos_jugados' => 90, 'goles' => 1, 'asistencias' => 1, 'tarjetas_amarillas' => 1, 'tarjetas_rojas' => 0],
            ['tenant_id' => 2, 'user_id' => 5, 'partido_id' => 5, 'minutos_jugados' => 60, 'goles' => 0, 'asistencias' => 0, 'tarjetas_amarillas' => 0, 'tarjetas_rojas' => 0],
            // Partido 6 (Tenant 2) - User 4 y 6
            ['tenant_id' => 2, 'user_id' => 4, 'partido_id' => 6, 'minutos_jugados' => 90, 'goles' => 2, 'asistencias' => 0, 'tarjetas_amarillas' => 0, 'tarjetas_rojas' => 0],
            ['tenant_id' => 2, 'user_id' => 6, 'partido_id' => 6, 'minutos_jugados' => 90, 'goles' => 0, 'asistencias' => 1, 'tarjetas_amarillas' => 1, 'tarjetas_rojas' => 0],
            // Partido 8 (Tenant 3) - User 7 y 8
            ['tenant_id' => 3, 'user_id' => 7, 'partido_id' => 8, 'minutos_jugados' => 90, 'goles' => 1, 'asistencias' => 0, 'tarjetas_amarillas' => 0, 'tarjetas_rojas' => 0],
            ['tenant_id' => 3, 'user_id' => 8, 'partido_id' => 8, 'minutos_jugados' => 80, 'goles' => 0, 'asistencias' => 2, 'tarjetas_amarillas' => 0, 'tarjetas_rojas' => 0],
        ];

        foreach ($rendimientos as $rendimiento) {
            Rendimiento::create($rendimiento);
        }
    }
}
