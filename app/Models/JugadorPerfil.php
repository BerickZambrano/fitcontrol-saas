<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;
use Laravel\Scout\Searchable;

class JugadorPerfil extends Model
{
    use BelongsToTenant, Searchable;

    protected $table = 'jugador_perfiles';

    protected static function booted()
    {
        parent::booted();

        static::creating(function (JugadorPerfil $model) {
            if ($model->user_id) {
                $user = User::find($model->user_id);
                if ($user && $user->tenant_id) {
                    $model->tenant_id = $user->tenant_id;
                }
            }
        });

        static::updating(function (JugadorPerfil $model) {
            if ($model->user_id) {
                $user = User::find($model->user_id);
                if ($user && $user->tenant_id) {
                    $model->tenant_id = $user->tenant_id;
                }
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'user_id',
        'posicion',
        'dorsal',
        'altura',
        'peso',
        'pierna_habil',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'jugador' => $this->user ? $this->user->name : '',
            'posicion' => $this->posicion,
            'dorsal' => (string) $this->dorsal,
            'pierna_habil' => $this->pierna_habil ?? '',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
