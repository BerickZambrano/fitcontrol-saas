<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipo extends Model
{
    use BelongsToTenant, Searchable, SoftDeletes, HasFactory;

    protected $fillable = [
        'tenant_id',
        'nombre',
        'logo_equipo',
        'ubi_equipo',
        'contacto_equipo',
        'categoria',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
            'ubicacion' => $this->ubi_equipo,
            'tenant_id' => $this->tenant_id,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
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

    public function jugadores()
    {
        return $this->belongsToMany(
            User::class,
            'historial_equipo',
            'id_equipo_fk',
            'id_jugador_fk'
        );
    }

    public function torneos()
{
    return $this->belongsToMany(Torneo::class, 'equipo_torneo')
        ->withTimestamps();
}

}
