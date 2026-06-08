<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Partido extends Model
{
    use BelongsToTenant, Searchable, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'fecha',
        'hora',
        'equipo_local_id',
        'equipo_visitante_id',
        'resultado',
        'fase',
        'torneo_id',
        'instalacion_id',
        'arbitro_id',
        'estado_arbitro',
        'estado_partido',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'local' => $this->local ? $this->local->nombre : '',
            'visitante' => $this->visitante ? $this->visitante->nombre : '',
            'torneo' => $this->torneo ? $this->torneo->nombre : '',
            'fecha' => $this->fecha,
            'resultado' => $this->resultado ?? '',
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

    public function local()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function visitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class, 'instalacion_id');
    }

    public function arbitro()
    {
        return $this->belongsTo(User::class, 'arbitro_id');
    }

    public function incidencias()
    {
        return $this->hasMany(IncidenciaPartido::class, 'partido_id');
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class, 'partido_id');
    }
}
