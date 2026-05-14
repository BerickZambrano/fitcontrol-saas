<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TenantRequestController;

use App\Http\Controllers\AdminRegisterController;

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

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use Illuminate\Support\Facades\Mail;

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


    use Inertia\Inertia;
use App\Http\Controllers\MailController;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

// Las rutas de API se han movido a routes/api.php


// Reportes - Descargar con verificación de tenant ownership
Route::get('/reportes/descargar/{report}', function (\App\Models\GeneratedReport $report) {
    $user = auth()->user();

    // Super admins can download any report; others only their tenant's
    if (!$user->hasRole('super_admin') && $report->tenant_id !== $user->tenant_id) {
        abort(403, 'No tienes permiso para descargar este reporte.');
    }

    $service = new \App\Services\ReportService();
    return $service->descargar($report);
})->middleware('auth')->name('reportes.descargar');

require __DIR__.'/settings.php';

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});



#
use App\Http\Controllers\OnboardingController;

Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
Route::post('/onboarding', [OnboardingController::class, 'store'])
    ->middleware('throttle:onboarding')
    ->name('onboarding.store');
Route::get('/onboarding/success', [OnboardingController::class, 'success'])->name('onboarding.success');


