<?php

namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Equipo extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'nombre',
        'logo_equipo',
        'ubi_equipo',
        'contacto_equipo',
        'categoria',
    ];

    // Scope global para filtrar por tenant
    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($query) {
            if (auth()->check()) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
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
