<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::create([
            'nombre'              => 'Academia Central',
            'nombre_corto'        => 'Central',
            'nit'                 => '900123456',
            'anio_fundacion'      => 2005,
            'tipo_club'           => 'formativo',
            'colores_oficiales'   => json_encode(['#FF0000', '#FFFFFF']),
            'escudo_url'          => null,
            'direccion'           => 'Calle 123 #45-67',
            'ciudad'              => 'Bogotá',
            'pais'                => 'Colombia',
            'email_corporativo'   => 'info@academiacentral.com',
            'telefono'            => '+57 300 1234567',
            'sitio_web'           => 'https://academiacentral.com',
            'subdominio'          => 'central',
            'estado'              => 'activo',
            'encargado_nombre'    => 'Juan Pérez',
            'encargado_email'     => 'juan.perez@academiacentral.com',
            'encargado_telefono'  => '+57 310 9876543',
        ]);

        Tenant::create([
            'nombre'              => 'Escuela Juvenil',
            'nombre_corto'        => 'Juvenil',
            'nit'                 => '900987654',
            'anio_fundacion'      => 2010,
            'tipo_club'           => 'amateur',
            'colores_oficiales'   => json_encode(['#0000FF', '#FFFF00']),
            'escudo_url'          => null,
            'direccion'           => 'Carrera 10 #20-30',
            'ciudad'              => 'Medellín',
            'pais'                => 'Colombia',
            'email_corporativo'   => 'contacto@escuelajuvenil.com',
            'telefono'            => '+57 320 1234567',
            'sitio_web'           => 'https://escuelajuvenil.com',
            'subdominio'          => 'juvenil',
            'estado'              => 'activo',
            'encargado_nombre'    => 'María López',
            'encargado_email'     => 'maria.lopez@escuelajuvenil.com',
            'encargado_telefono'  => '+57 310 7654321',
        ]);

        Tenant::create([
            'nombre'              => 'Club Profesional',
            'nombre_corto'        => 'Profesional',
            'nit'                 => '900555444',
            'anio_fundacion'      => 1995,
            'tipo_club'           => 'profesional',
            'colores_oficiales'   => json_encode(['#00FF00', '#000000']),
            'escudo_url'          => null,
            'direccion'           => 'Avenida 5 #15-50',
            'ciudad'              => 'Cali',
            'pais'                => 'Colombia',
            'email_corporativo'   => 'info@clubprofesional.com',
            'telefono'            => '+57 300 5554444',
            'sitio_web'           => 'https://clubprofesional.com',
            'subdominio'          => 'pro',
            'estado'              => 'activo',
            'encargado_nombre'    => 'Carlos Martínez',
            'encargado_email'     => 'carlos.martinez@clubprofesional.com',
            'encargado_telefono'  => '+57 310 5556666',
        ]);
    }
}