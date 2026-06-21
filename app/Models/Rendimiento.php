<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use App\Models\Traits\BelongsToTenant;

class Rendimiento extends Model
{
    use Searchable, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'partido_id',
        'minutos_jugados',
        'goles',
        'asistencias',
        'tarjetas_amarillas',
        'tarjetas_rojas',
        'evaluacion',
    ];

    protected $casts = [
        'minutos_jugados' => 'integer',
        'goles' => 'integer',
        'asistencias' => 'integer',
        'tarjetas_amarillas' => 'integer',
        'tarjetas_rojas' => 'integer',
        'evaluacion' => 'float',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'jugador' => $this->user ? $this->user->name : '',
            'goles' => (string) $this->goles,
            'asistencias' => (string) $this->asistencias,
            'minutos' => (string) $this->minutos_jugados,
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }
}
