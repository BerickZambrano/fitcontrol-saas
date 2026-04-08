<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
   protected $fillable = [
    'nombre',
    'subdominio',
    'estado',
    'nombre_corto',
    'nit',
    'anio_fundacion',
    'tipo_club',
    'colores_oficiales',
    'escudo_url',
    'direccion',
    'ciudad',
    'pais',
    'email_corporativo',
    'telefono',
    'sitio_web',
    'encargado_nombre',
    'encargado_email',      // ← para enviar la invitación
    'encargado_telefono',
    'register_token',
    'rut_document',         // ← nuevo
    'camara_comercio',      // ← nuevo
    'plan',                 // ← nuevo
    'rejection_reason',     // ← nuevo
];

    protected $casts = [
        'anio_fundacion'   => 'integer',
        'colores_oficiales'=> 'array',
    ];

  // Relaciones
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'encargado_user_id');
    }
}