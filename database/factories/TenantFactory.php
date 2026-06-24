<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $nombre = $this->faker->company();
        return [
            'nombre' => $nombre,
            'subdominio' => Str::slug($nombre),
            'estado' => 'activo',
            'onboarding_completed' => true,
            'nombre_corto' => Str::upper(Str::limit($nombre, 3, '')),
            'nit' => $this->faker->unique()->numerify('#########'),
            'anio_fundacion' => 2026,
            'tipo_club' => 'formativo',
            'colores_oficiales' => ['primary' => '#1e3a8a'],
            'direccion' => $this->faker->address(),
            'ciudad' => $this->faker->city(),
            'pais' => 'Colombia',
            'email_corporativo' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'encargado_nombre' => $this->faker->name(),
            'encargado_email' => $this->faker->unique()->safeEmail(),
            'plan' => 'mensual',
            'estado_pago' => 'pagado',
        ];
    }
}
