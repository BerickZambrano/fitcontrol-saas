<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos@tenant1.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 1,
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@tenant1.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 1,
            ],
            [
                'name' => 'Luis Fernández',
                'email' => 'luis@tenant1.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 1,
            ],
            [
                'name' => 'María González',
                'email' => 'maria@tenant2.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 2,
            ],
            [
                'name' => 'Pedro Sánchez',
                'email' => 'pedro@tenant2.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 2,
            ],
            [
                'name' => 'Laura Díaz',
                'email' => 'laura@tenant2.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 2,
            ],
            [
                'name' => 'Jorge López',
                'email' => 'jorge@tenant3.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 3,
            ],
            [
                'name' => 'Sofía Herrera',
                'email' => 'sofia@tenant3.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 3,
            ],
            [
                'name' => 'Diego Torres',
                'email' => 'diego@tenant3.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 3,
            ],
            [
                'name' => 'Valentina Ruiz',
                'email' => 'valentina@tenant1.com',
                'password' => Hash::make('password123'),
                'tenant_id' => 1,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
