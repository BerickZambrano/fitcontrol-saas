<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Models\Traits\BelongsToTenant;

class HistorialMedico extends Model
{
    use Searchable, BelongsToTenant;

    protected $table = 'historial_medico';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'tipo_lesion',
        'descripcion',
        'gravedad',
        'fecha_inicio',
        'fecha_fin',
        'apto',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'jugador' => $this->usuario ? $this->usuario->name : '',
            'tipo_lesion' => $this->tipo_lesion,
            'descripcion' => $this->descripcion,
            'gravedad' => $this->gravedad,
            'tenant_id' => $this->tenant_id ?? 0,
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

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
