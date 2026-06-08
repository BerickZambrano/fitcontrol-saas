<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sancion extends Model
{
    use SoftDeletes;

    protected $table = 'sanciones';

    protected $fillable = [
        'jugador_id',
        'partido_id_origen',
        'cantidad_partidos_suspension',
        'partidos_cumplidos',
        'estado',
    ];

    public function jugador()
    {
        return $this->belongsTo(User::class, 'jugador_id');
    }

    public function partidoOrigen()
    {
        return $this->belongsTo(Partido::class, 'partido_id_origen');
    }
}
