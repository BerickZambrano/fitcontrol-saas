<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rendimiento extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'partido_id',
        'minutos_jugados',
        'goles',
        'asistencias',
        'tarjetas_amarillas',
        'tarjetas_rojas',
    ];

    protected $casts = [
        'minutos_jugados' => 'integer',
        'goles' => 'integer',
        'asistencias' => 'integer',
        'tarjetas_amarillas' => 'integer',
        'tarjetas_rojas' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }
}
