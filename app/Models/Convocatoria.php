<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Convocatoria extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'partido_id',
        'jugador_id',
        'equipo_id',
        'estado_asistencia',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(User::class, 'jugador_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
