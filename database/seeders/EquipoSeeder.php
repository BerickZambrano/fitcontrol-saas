<?php

namespace Database\Seeders;

use App\Models\Equipo;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        $equipos = [
            // Tenant 1 - Academia Central
            [
                'tenant_id' => 1,
                'nombre' => 'Academia Central A',
                'categoria' => 'profesional',
                'logo_equipo' => null,
                'ubi_equipo' => 'Bogotá',
                'contacto_equipo' => '+57 300 1111111',
                'categoria_equipo' => 'Primera División',
            ],
            [
                'tenant_id' => 1,
                'nombre' => 'Academia Central B',
                'categoria' => 'formativo',
                'logo_equipo' => null,
                'ubi_equipo' => 'Bogotá',
                'contacto_equipo' => '+57 300 2222222',
                'categoria_equipo' => 'Juvenil',
            ],
            [
                'tenant_id' => 1,
                'nombre' => 'Academia Central C',
                'categoria' => 'amateur',
                'logo_equipo' => null,
                'ubi_equipo' => 'Bogotá',
                'contacto_equipo' => '+57 300 3333333',
                'categoria_equipo' => 'Sub-15',
            ],
            [
                'tenant_id' => 1,
                'nombre' => 'Academia Central Femenino',
                'categoria' => 'profesional',
                'logo_equipo' => null,
                'ubi_equipo' => 'Bogotá',
                'contacto_equipo' => '+57 300 4444444',
                'categoria_equipo' => 'Femenino',
            ],
            // Tenant 2 - Escuela Juvenil
            [
                'tenant_id' => 2,
                'nombre' => 'Juvenil Sub-17',
                'categoria' => 'formativo',
                'logo_equipo' => null,
                'ubi_equipo' => 'Medellín',
                'contacto_equipo' => '+57 310 5555555',
                'categoria_equipo' => 'Sub-17',
            ],
            [
                'tenant_id' => 2,
                'nombre' => 'Juvenil Sub-20',
                'categoria' => 'formativo',
                'logo_equipo' => null,
                'ubi_equipo' => 'Medellín',
                'contacto_equipo' => '+57 310 6666666',
                'categoria_equipo' => 'Sub-20',
            ],
            [
                'tenant_id' => 2,
                'nombre' => 'Juvenil Amateur',
                'categoria' => 'amateur',
                'logo_equipo' => null,
                'ubi_equipo' => 'Medellín',
                'contacto_equipo' => '+57 310 7777777',
                'categoria_equipo' => 'Amateur',
            ],
            // Tenant 3 - Club Profesional
            [
                'tenant_id' => 3,
                'nombre' => 'Club Pro Elite',
                'categoria' => 'profesional',
                'logo_equipo' => null,
                'ubi_equipo' => 'Cali',
                'contacto_equipo' => '+57 320 8888888',
                'categoria_equipo' => 'Primera A',
            ],
            [
                'tenant_id' => 3,
                'nombre' => 'Club Pro Reservas',
                'categoria' => 'profesional',
                'logo_equipo' => null,
                'ubi_equipo' => 'Cali',
                'contacto_equipo' => '+57 320 9999999',
                'categoria_equipo' => 'Reservas',
            ],
            [
                'tenant_id' => 3,
                'nombre' => 'Club Pro Formativo',
                'categoria' => 'formativo',
                'logo_equipo' => null,
                'ubi_equipo' => 'Cali',
                'contacto_equipo' => '+57 320 0000000',
                'categoria_equipo' => 'Sub-18',
            ],
        ];

        foreach ($equipos as $equipo) {
            Equipo::create($equipo);
        }
    }
}
