<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    public function show($token)
    {
        $tenant = Tenant::where('register_token', $token)->firstOrFail();
        return view('auth.register-admin', compact('tenant'));
    }

    public function store(Request $request, $token)
    {
        $tenant = Tenant::where('register_token', $token)->firstOrFail();

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',            // 1 Mayúscula
                'regex:/[^A-Za-z0-9]/',     // 1 Carácter especial
                'confirmed',
            ],
        ], [
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex'     => 'La contraseña debe contener al menos una letra mayúscula y un carácter especial.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'Debes ingresar un correo electrónico válido.',
            'email.unique'       => 'Este correo electrónico ya está registrado en el sistema.',
            'name.required'      => 'El nombre completo es obligatorio.',
        ]);

        // Prevent race condition: only one admin per tenant
        $lock = Cache::lock("admin_register_{$tenant->id}", 10);

        if (! $lock->get()) {
            abort(429, 'Este equipo ya está siendo registrado. Intenta en unos segundos.');
        }

        try {
            if (User::where('tenant_id', $tenant->id)->exists()) {
                abort(403, 'Este equipo ya tiene administrador.');
            }

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
            ]);

            $user->assignRole('Administrador');

            $tenant->update(['register_token' => null]);

            auth()->login($user);
        } finally {
            $lock->release();
        }

        // Redirect to admin — middleware will redirect to onboarding if needed
        return redirect('/admin');
    }
}