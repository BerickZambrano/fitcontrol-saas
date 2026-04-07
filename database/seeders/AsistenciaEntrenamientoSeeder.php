<?php

namespace Database\Seeders;

use App\Models\AsistenciaEntrenamiento;
use Illuminate\Database\Seeder;

class AsistenciaEntrenamientoSeeder extends Seeder
{
    public function run(): void
    {
        $asistencias = [
            // Entrenamiento 1 - User 1 presente, User 2 presente
            ['tenant_id' => 1, 'entrenamiento_id' => 1, 'user_id' => 1, 'presente' => true],
            ['tenant_id' => 1, 'entrenamiento_id' => 1, 'user_id' => 2, 'presente' => true],
            // Entrenamiento 2 - User 1 presente, User 2 ausente
            ['tenant_id' => 1, 'entrenamiento_id' => 2, 'user_id' => 1, 'presente' => true],
            ['tenant_id' => 1, 'entrenamiento_id' => 2, 'user_id' => 2, 'presente' => false],
            // Entrenamiento 5 - User 4 presente, User 5 presente
            ['tenant_id' => 2, 'entrenamiento_id' => 6, 'user_id' => 4, 'presente' => true],
            ['tenant_id' => 2, 'entrenamiento_id' => 6, 'user_id' => 5, 'presente' => true],
            // Entrenamiento 7 - User 5 ausente, User 6 presente
            ['tenant_id' => 2, 'entrenamiento_id' => 7, 'user_id' => 5, 'presente' => false],
            ['tenant_id' => 2, 'entrenamiento_id' => 7, 'user_id' => 6, 'presente' => true],
            // Entrenamiento 8 - User 7 presente, User 8 presente
            ['tenant_id' => 3, 'entrenamiento_id' => 8, 'user_id' => 7, 'presente' => true],
            ['tenant_id' => 3, 'entrenamiento_id' => 8, 'user_id' => 8, 'presente' => true],
        ];

        foreach ($asistencias as $asistencia) {
            AsistenciaEntrenamiento::create($asistencia);
        }
    }
}
