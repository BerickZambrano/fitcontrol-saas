<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantRequestController extends Controller
{
    public function create()
    {
        return view('tenant.request');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|unique:tenants,nit',
            'tipo_club' => 'required|in:formativo,amateur,profesional',
            'ciudad' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'email_corporativo' => 'required|email|unique:tenants,email_corporativo',
            'encargado_nombre' => 'required|string|max:255',
            'encargado_email' => 'required|email',
        ]);

        Tenant::create([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'tipo_club' => $request->tipo_club,
            'ciudad' => $request->ciudad,
            'pais' => $request->pais,
            'email_corporativo' => $request->email_corporativo,
            'encargado_nombre' => $request->encargado_nombre,
            'encargado_email' => $request->encargado_email,
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('tenant.request')
            ->with('success', 'Solicitud enviada correctamente. Será revisada por el administrador.');
    }
}