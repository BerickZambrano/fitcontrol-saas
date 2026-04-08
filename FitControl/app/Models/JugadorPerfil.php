<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class JugadorPerfil extends Model
{
    use BelongsToTenant;

    protected $table = 'jugador_perfiles';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'posicion',
        'dorsal',
        'altura',
        'peso',
        'pierna_habil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
