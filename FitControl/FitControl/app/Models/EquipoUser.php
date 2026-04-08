<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class EquipoUser extends Model
{
    use BelongsToTenant;

    protected $table = 'equipo_user';

    protected $fillable = [
        'id',
        'tenant_id',
        'equipo_id',
        'user_id',
        'fecha_inicio',
        'fecha_fin',
    ];
    public function equipo()
{
    return $this->belongsTo(Equipo::class);
}

public function jugador()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
