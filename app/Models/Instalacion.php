<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Instalacion extends Model
{
    protected $table = 'instalaciones';

    protected $fillable = [
        'tenant_id',
        'nombre',
        'tipo',
        'ubicacion',
        'capacidad',
        'estado',
    ];

    protected static function booted()
    {
        // Scope global por tenant
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });

        // Asignar tenant_id automáticamente al crear
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
