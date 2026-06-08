<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidenciaPartido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'partido_id',
        'jugador_id',
        'tipo',
        'minuto',
        'descripcion',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(User::class, 'jugador_id');
    }
}
