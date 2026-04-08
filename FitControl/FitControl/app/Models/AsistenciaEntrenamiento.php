<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class AsistenciaEntrenamiento extends Model
{
    use BelongsToTenant;

    protected $table = 'asistencia_entrenamiento';

    protected $fillable = [
        'tenant_id',
        'entrenamiento_id',
        'user_id',
        'presente',
    ];

    protected $casts = [
        'presente' => 'boolean',
    ];

    public function setPresenteAttribute($value): void
    {
        $this->attributes['presente'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

    public function entrenamiento()
    {
        return $this->belongsTo(Entrenamiento::class, 'entrenamiento_id');
    }

    public function jugador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}