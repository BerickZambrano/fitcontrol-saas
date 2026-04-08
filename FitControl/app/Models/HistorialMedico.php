<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialMedico extends Model
{
    protected $table = 'historial_medico';

    protected $fillable = [
        'user_id',
        'tipo_lesion',
        'descripcion',
        'gravedad',
        'fecha_inicio',
        'fecha_fin',
        'apto',
    ];
    // Boot method para asignar tenant automáticamente
    protected static function booted()
    {
        static::creating(function ($model) {
            // Si no se pasó tenant_id, se asigna automáticamente desde el usuario autenticado
            if (empty($model->tenant_id) && auth()->check()) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
