<?php

namespace Database\Seeders;

use App\Models\Partido;
use Illuminate\Database\Seeder;

class PartidoSeeder extends Seeder
{
    public function run(): void
    {
        $partidos = [
            // Tenant 1 - Torneo 1 - Equipo 1 vs Equipo 2
            [
                'tenant_id' => 1,
                'torneo_id' => 1,
                'equipo_local_id' => 1,
                'equipo_visitante_id' => 2,
                'fecha' => '2026-03-15',
                'hora' => '15:00:00',
                'resultado' => '3-1',
            ],
            [
                'tenant_id' => 1,
                'torneo_id' => 1,
                'equipo_local_id' => 2,
                'equipo_visitante_id' => 1,
                'fecha' => '2026-03-22',
                'hora' => '16:00:00',
                'resultado' => '2-2',
            ],
            [
                'tenant_id' => 1,
                'torneo_id' => 1,
                'equipo_local_id' => 1,
                'equipo_visitante_id' => 3,
                'fecha' => '2026-03-29',
                'hora' => '14:00:00',
                'resultado' => '4-0',
            ],
            [
                'tenant_id' => 1,
                'torneo_id' => 1,
                'equipo_local_id' => 3,
                'equipo_visitante_id' => 1,
                'fecha' => '2026-04-05',
                'hora' => '15:30:00',
                'resultado' => '1-2',
            ],
            // Tenant 2 - Torneo 5 - Equipo 5 vs Equipo 6
            [
                'tenant_id' => 2,
                'torneo_id' => 5,
                'equipo_local_id' => 5,
                'equipo_visitante_id' => 6,
                'fecha' => '2026-03-18',
                'hora' => '10:00:00',
                'resultado' => '2-1',
            ],
            [
                'tenant_id' => 2,
                'torneo_id' => 5,
                'equipo_local_id' => 6,
                'equipo_visitante_id' => 5,
                'fecha' => '2026-03-25',
                'hora' => '11:00:00',
                'resultado' => '3-3',
            ],
            [
                'tenant_id' => 2,
                'torneo_id' => 5,
                'equipo_local_id' => 5,
                'equipo_visitante_id' => 7,
                'fecha' => '2026-04-01',
                'hora' => '09:00:00',
                'resultado' => '1-0',
            ],
            // Tenant 3 - Torneo 8 - Equipo 8 vs Equipo 9
            [
                'tenant_id' => 3,
                'torneo_id' => 8,
                'equipo_local_id' => 8,
                'equipo_visitante_id' => 9,
                'fecha' => '2026-03-20',
                'hora' => '18:00:00',
                'resultado' => '2-0',
            ],
            [
                'tenant_id' => 3,
                'torneo_id' => 8,
                'equipo_local_id' => 9,
                'equipo_visitante_id' => 8,
                'fecha' => '2026-03-27',
                'hora' => '19:00:00',
                'resultado' => '1-1',
            ],
            [
                'tenant_id' => 3,
                'torneo_id' => 8,
                'equipo_local_id' => 8,
                'equipo_visitante_id' => 10,
                'fecha' => '2026-04-03',
                'hora' => '20:00:00',
                'resultado' => '3-2',
            ],
        ];

        foreach ($partidos as $partido) {
            Partido::create($partido);
        }
    }
}
