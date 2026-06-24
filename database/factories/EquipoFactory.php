<?php

namespace Database\Factories;

use App\Models\Equipo;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipoFactory extends Factory
{
    protected $model = Equipo::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'nombre' => $this->faker->word() . ' FC',
            'logo_equipo' => null,
            'ubi_equipo' => $this->faker->city(),
            'contacto_equipo' => $this->faker->phoneNumber(),
            'categoria' => 'formativo',
        ];
    }
}
