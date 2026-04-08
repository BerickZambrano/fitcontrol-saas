<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;
use Filament\Facades\Filament;

class Entrenamiento extends Model
{
    use BelongsToTenant;

    protected $fillable = [ 
        'nombre',
        'fecha',
        'hora',
        'ubicacion',
        'equipo_id',
        'tenant_id',
    ];

    // RELACIONES
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function asistencias()
    {
        return $this->hasMany(AsistenciaEntrenamiento::class, 'entrenamiento_id');
    }
}