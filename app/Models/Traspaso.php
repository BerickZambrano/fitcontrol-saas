<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
    protected $fillable = [
        'jugador_id',
        'equipo_origen_id',
        'equipo_destino_id',
        'estado',
    ];

    public function jugador()
    {
        return $this->belongsTo(User::class, 'jugador_id');
    }

    public function equipoOrigen()
    {
        return $this->belongsTo(Equipo::class, 'equipo_origen_id');
    }

    public function equipoDestino()
    {
        return $this->belongsTo(Equipo::class, 'equipo_destino_id');
    }
}
