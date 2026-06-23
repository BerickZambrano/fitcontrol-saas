<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantRequestController;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaywallController;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

Route::middleware('web')->group(function () {
    Route::get('/paywall', [PaywallController::class, 'index'])->name('paywall.index');
    Route::post('/paywall/prepare', [PaywallController::class, 'prepare'])->name('paywall.prepare');
    Route::get('/paywall/callback', [PaywallController::class, 'callback'])->name('paywall.callback');
    Route::post('/paywall/simulate-payment', [PaywallController::class, 'simulatePayment'])->name('paywall.simulate-payment');
});

Route::get('/debug-partidos', function() {
    $equiposIds = [5, 6]; // just testing
    return \App\Models\User::role('Jugador')
        ->whereHas('equipoUser', function ($query) use ($equiposIds) {
            $query->whereIn('equipo_id', $equiposIds);
        })->get();
});

Route::get('/register-admin/{token}', [AdminRegisterController::class, 'show'])
    ->name('register.admin');

Route::post('/register-admin/{token}', [AdminRegisterController::class, 'store'])
    ->middleware('throttle:admin-register')
    ->name('register.admin.store');

Route::get('/2fa/verify', \App\Livewire\Auth\TwoFactorVerify::class)
    ->name('2fa.verify');

Route::get('/solicitar-acceso', [TenantRequestController::class, 'create'])->name('tenant.request');

Route::post('/solicitar-acceso', [TenantRequestController::class, 'store'])
    ->middleware('throttle:tenant-request')
    ->name('tenant.request.store');

Route::get('dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole(['Administrador', 'super_admin'])) {
        return redirect('/admin');
    }

    if ($user->hasRole('Entrenador')) {
        return redirect('/entrenador');
    }

    if ($user->hasRole('Arbitro')) {
        return redirect('/arbitro');
    }

    if ($user->hasRole('Medico')) {
        return redirect('/medico');
    }

    return redirect('/jugador');
})->middleware(['auth', 'verified'])->name('dashboard');

// Solo disponible en entorno local (desarrollo)
if (app()->environment('local')) {
    Route::get('/test-mail', function () {
        Mail::raw('Correo de prueba desde FitControl SaaS', function ($message) {
            $message->to('beritozambrano@gmail.com')
                    ->subject('Prueba de correo');
        });

        return 'Correo enviado';
    });
}


Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');


// Reportes - Descargar con verificación de tenant ownership
Route::get('/reportes/descargar/{report}', [ReportController::class, 'download'])
    ->middleware('auth')
    ->name('reportes.descargar');

require __DIR__.'/settings.php';

Route::get('/admin/tenants/{tenant}/document/{field}', [App\Http\Controllers\Admin\TenantDocumentController::class, 'show'])
    ->middleware(['auth'])
    ->name('admin.tenants.document.show');

//Rutas del onboarding
Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
Route::post('/onboarding', [OnboardingController::class, 'store'])
    ->middleware('throttle:onboarding')
    ->name('onboarding.store');
Route::get('/onboarding/success', [OnboardingController::class, 'success'])->name('onboarding.success');

Route::get('/terminos-y-condiciones', function () {
    return view('terminos');
})->name('terminos');

Route::get('/terminos', function () {
    return view('terminos');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

