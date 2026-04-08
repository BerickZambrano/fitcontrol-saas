<?php

namespace Database\Seeders;

use App\Models\Entrenamiento;
use Illuminate\Database\Seeder;

class EntrenamientoSeeder extends Seeder
{
    public function run(): void
    {
        $entrenamientos = [
            // Tenant 1 - Equipo 1
            [
                'tenant_id' => 1,
                'equipo_id' => 1,
                'nombre' => 'Entrenamiento Táctico',
                'fecha' => '2026-03-10',
                'hora' => '08:00:00',
                'ubicacion' => 'Cancha Principal',
            ],
            [
                'tenant_id' => 1,
                'equipo_id' => 1,
                'nombre' => 'Entrenamiento Físico',
                'fecha' => '2026-03-12',
                'hora' => '09:00:00',
                'ubicacion' => 'Gimnasio',
            ],
            [
                'tenant_id' => 1,
                'equipo_id' => 1,
                'nombre' => 'Práctica de Tiro',
                'fecha' => '2026-03-15',
                'hora' => '10:00:00',
                'ubicacion' => 'Cancha 2',
            ],
            // Tenant 1 - Equipo 2
            [
                'tenant_id' => 1,
                'equipo_id' => 2,
                'nombre' => 'Entrenamiento Básico',
                'fecha' => '2026-03-11',
                'hora' => '14:00:00',
                'ubicacion' => 'Cancha Juvenil',
            ],
            [
                'tenant_id' => 1,
                'equipo_id' => 2,
                'nombre' => 'Práctica de Pase',
                'fecha' => '2026-03-14',
                'hora' => '15:00:00',
                'ubicacion' => 'Cancha Principal',
            ],
            // Tenant 2 - Equipo 5
            [
                'tenant_id' => 2,
                'equipo_id' => 5,
                'nombre' => 'Entrenamiento Sub-17',
                'fecha' => '2026-03-10',
                'hora' => '16:00:00',
                'ubicacion' => 'Cancha Medellín',
            ],
            [
                'tenant_id' => 2,
                'equipo_id' => 5,
                'nombre' => 'Preparación Física',
                'fecha' => '2026-03-13',
                'hora' => '17:00:00',
                'ubicacion' => 'Gimnasio Central',
            ],
            // Tenant 3 - Equipo 8
            [
                'tenant_id' => 3,
                'equipo_id' => 8,
                'nombre' => 'Entrenamiento Élite',
                'fecha' => '2026-03-10',
                'hora' => '07:00:00',
                'ubicacion' => 'Estadio Principal',
            ],
            [
                'tenant_id' => 3,
                'equipo_id' => 8,
                'nombre' => 'Táctica Avanzada',
                'fecha' => '2026-03-12',
                'hora' => '08:30:00',
                'ubicacion' => 'Cancha Alterna',
            ],
            [
                'tenant_id' => 3,
                'equipo_id' => 8,
                'nombre' => 'Análisis de Rival',
                'fecha' => '2026-03-16',
                'hora' => '10:00:00',
                'ubicacion' => 'Sala de Video',
            ],
        ];

        foreach ($entrenamientos as $entrenamiento) {
            Entrenamiento::create($entrenamiento);
        }
    }
}
