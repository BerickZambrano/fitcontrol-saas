<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;
use Laravel\Scout\Searchable;

class Torneo extends Model
{
    use BelongsToTenant, Searchable;

    protected $fillable = [
        'nombre',
        'categoria',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'tenant_id',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
            'estado' => $this->estado,
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

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }
}
