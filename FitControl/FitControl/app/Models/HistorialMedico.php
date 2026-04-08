<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class HistorialMedico extends Model
{
    use Searchable;

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

    // Boot method para asignar tenant automáticamente
    protected static function booted()
    {
        static::creating(function ($model) {
            // Si no se pasó tenant_id, se asigna automáticamente desde el usuario autenticado
            if (empty($model->tenant_id) && auth()->check()) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
