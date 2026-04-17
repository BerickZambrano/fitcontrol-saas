<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Entrenamiento extends Model
{
    use BelongsToTenant, Searchable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'fecha',
        'hora',
        'ubicacion',
        'equipo_id',
        'tenant_id',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'ubicacion' => $this->ubicacion,
            'fecha' => $this->fecha,
            'equipo' => $this->equipo ? $this->equipo->nombre : '',
            'tenant_id' => $this->tenant_id,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
    }

    public function getScoutKey(): mixed
    {
        return $this->getKey();
    }

    public function getScoutKeyName(): mixed
    {
        return $this->getKeyName();
    }

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