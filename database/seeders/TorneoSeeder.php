<?php

namespace Database\Seeders;

use App\Models\Torneo;
use Illuminate\Database\Seeder;

class TorneoSeeder extends Seeder
{
    public function run(): void
    {
        $torneos = [
            // Tenant 1
            [
                'tenant_id' => 1,
                'nombre' => 'Liga Nacional 2026',
                'categoria' => 'Primera División',
                'fecha_inicio' => '2026-02-01',
                'fecha_fin' => '2026-11-30',
                'estado' => 'activo',
            ],
            [
                'tenant_id' => 1,
                'nombre' => 'Copa Nacional 2026',
                'categoria' => 'Primera División',
                'fecha_inicio' => '2026-03-01',
                'fecha_fin' => '2026-10-15',
                'estado' => 'activo',
            ],
            [
                'tenant_id' => 1,
                'nombre' => 'Torneo Juvenil 2026',
                'categoria' => 'Juvenil',
                'fecha_inicio' => '2026-01-15',
                'fecha_fin' => '2026-08-30',
                'estado' => 'activo',
            ],
            [
                'tenant_id' => 1,
                'nombre' => 'Copa Formativa 2026',
                'categoria' => 'Formativo',
                'fecha_inicio' => '2026-02-15',
                'fecha_fin' => '2026-09-30',
                'estado' => 'activo',
            ],
            // Tenant 2
            [
                'tenant_id' => 2,
                'nombre' => 'Torneo Sub-17 2026',
                'categoria' => 'Sub-17',
                'fecha_inicio' => '2026-03-01',
                'fecha_fin' => '2026-10-30',
                'estado' => 'activo',
            ],
            [
                'tenant_id' => 2,
                'nombre' => 'Torneo Sub-20 2026',
                'categoria' => 'Sub-20',
                'fecha_inicio' => '2026-02-15',
                'fecha_fin' => '2026-11-15',
                'estado' => 'activo',
            ],
            [
                'tenant_id' => 2,
                'nombre' => 'Liga Amateur 2026',
                'categoria' => 'Amateur',
                'fecha_inicio' => '2026-04-01',
                'fecha_fin' => '2026-12-15',
                'estado' => 'activo',
            ],
            // Tenant 3
            [
                'tenant_id' => 3,
                'nombre' => 'Liga Profesional 2026',
                'categoria' => 'Primera A',
                'fecha_inicio' => '2026-01-20',
                'fecha_fin' => '2026-12-10',
                'estado' => 'activo',
            ],
            [
                'tenant_id' => 3,
                'nombre' => 'Copa Élite 2026',
                'categoria' => 'Primera A',
                'fecha_inicio' => '2026-03-15',
                'fecha_fin' => '2026-11-20',
                'estado' => 'activo',
            ],
            [
                'tenant_id' => 3,
                'nombre' => 'Torneo Reservas 2026',
                'categoria' => 'Reservas',
                'fecha_inicio' => '2026-02-10',
                'fecha_fin' => '2026-10-30',
                'estado' => 'activo',
            ],
        ];

        foreach ($torneos as $torneo) {
            Torneo::create($torneo);
        }
    }
}
