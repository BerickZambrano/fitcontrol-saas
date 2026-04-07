<?php

namespace Database\Seeders;

use App\Models\HistorialMedico;
use Illuminate\Database\Seeder;

class HistorialMedicoSeeder extends Seeder
{
    public function run(): void
    {
        $historiales = [
            // Tenant 1 - User 1 con lesión
            ['tenant_id' => 1, 'user_id' => 1, 'tipo_lesion' => 'Esguince de tobillo', 'gravedad' => 'Leve', 'descripcion' => 'Esguince grado 1 en tobillo derecho', 'fecha_inicio' => '2026-03-05', 'fecha_fin' => '2026-03-20', 'apto' => true],
            // Tenant 1 - User 3 con lesión
            ['tenant_id' => 1, 'user_id' => 3, 'tipo_lesion' => 'Contractura muscular', 'gravedad' => 'Moderada', 'descripcion' => 'Contractura en isquiotibiales', 'fecha_inicio' => '2026-03-10', 'fecha_fin' => '2026-04-10', 'apto' => true],
            // Tenant 1 - User 2 sin lesión (control)
            ['tenant_id' => 1, 'user_id' => 2, 'tipo_lesion' => 'Control médico', 'gravedad' => 'Ninguna', 'descripcion' => 'Revisión médica rutinaria', 'fecha_inicio' => '2026-03-01', 'fecha_fin' => '2026-03-01', 'apto' => true],
            // Tenant 2 - User 4 con lesión grave
            ['tenant_id' => 2, 'user_id' => 4, 'tipo_lesion' => 'Rotura de ligamentos', 'gravedad' => 'Grave', 'descripcion' => 'Rotura parcial de LCA en rodilla izquierda', 'fecha_inicio' => '2026-02-15', 'fecha_fin' => null, 'apto' => false],
            // Tenant 2 - User 5 con lesión
            ['tenant_id' => 2, 'user_id' => 5, 'tipo_lesion' => 'Fractura de metatarsiano', 'gravedad' => 'Moderada', 'descripcion' => 'Fractura del quinto metatarsiano', 'fecha_inicio' => '2026-03-12', 'fecha_fin' => '2026-05-12', 'apto' => false],
            // Tenant 2 - User 6 apto
            ['tenant_id' => 2, 'user_id' => 6, 'tipo_lesion' => 'Control médico', 'gravedad' => 'Ninguna', 'descripcion' => 'Examen médico periódico', 'fecha_inicio' => '2026-03-20', 'fecha_fin' => '2026-03-20', 'apto' => true],
            // Tenant 3 - User 7 con lesión
            ['tenant_id' => 3, 'user_id' => 7, 'tipo_lesion' => 'Desgarro muscular', 'gravedad' => 'Leve', 'descripcion' => 'Desgarro grado 1 en gemelo', 'fecha_inicio' => '2026-03-18', 'fecha_fin' => '2026-04-01', 'apto' => true],
            // Tenant 3 - User 8 con lesión
            ['tenant_id' => 3, 'user_id' => 8, 'tipo_lesion' => 'Tendinitis rotuliana', 'gravedad' => 'Moderada', 'descripcion' => 'Inflamación del tendón rotuliano', 'fecha_inicio' => '2026-03-10', 'fecha_fin' => '2026-04-15', 'apto' => true],
            // Tenant 3 - User 9 con lesión grave
            ['tenant_id' => 3, 'user_id' => 9, 'tipo_lesion' => 'Lesión de hombro', 'gravedad' => 'Grave', 'descripcion' => 'Luxación de hombro derecho', 'fecha_inicio' => '2026-03-25', 'fecha_fin' => null, 'apto' => false],
            // Tenant 1 - User 10 con control
            ['tenant_id' => 1, 'user_id' => 10, 'tipo_lesion' => 'Control médico', 'gravedad' => 'Ninguna', 'descripcion' => 'Revisión médica anual', 'fecha_inicio' => '2026-03-28', 'fecha_fin' => '2026-03-28', 'apto' => true],
        ];

        foreach ($historiales as $historial) {
            HistorialMedico::create($historial);
        }
    }
}
