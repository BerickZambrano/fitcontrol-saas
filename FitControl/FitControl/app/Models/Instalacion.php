<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Instalacion extends Model
{
    use BelongsToTenant; // 👈 ESTO FALTABA

    protected $table = 'instalaciones';
    
    protected $fillable = [
        'tenant_id', // recomendado
        'nombre',
        'tipo',
        'ubicacion',
        'capacidad',
        'estado',
    ];
}
