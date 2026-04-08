<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;
use Laravel\Scout\Searchable;

class Partido extends Model
{
    use BelongsToTenant, Searchable;

    protected $fillable = [
        'tenant_id',
        'fecha',
        'hora',
        'equipo_local_id',
        'equipo_visitante_id',
        'resultado',
        'torneo_id',
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

    // Asignar tenant automáticamente
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });

        // Global scope por tenant
        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check()) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
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

}
