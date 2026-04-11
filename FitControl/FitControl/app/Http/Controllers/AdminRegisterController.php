<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
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
            'password' => 'required|min:8|confirmed',
        ]);

        if (User::where('tenant_id', $tenant->id)->exists()) {
            abort(403, 'Este tenant ya tiene administrador.');
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

        // Redirect to admin — middleware will redirect to onboarding if needed
        return redirect('/admin');
    }
}