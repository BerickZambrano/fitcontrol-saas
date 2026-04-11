<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TenantRequestController;

use App\Http\Controllers\AdminRegisterController;

Route::get('/register-admin/{token}', [AdminRegisterController::class, 'show'])
    ->name('register.admin');

Route::post('/register-admin/{token}', [AdminRegisterController::class, 'store']);

Route::get('/solicitar-acceso', [TenantRequestController::class, 'create'])->name('tenant.request');
Route::post('/solicitar-acceso', [TenantRequestController::class, 'store'])->name('tenant.request.store');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::raw('Correo de prueba desde FitControl SaaS', function ($message) {
        $message->to('beritozambrano@gmail.com')
                ->subject('Prueba de correo');
    });

    return 'Correo enviado';
});


    use Inertia\Inertia;
use App\Http\Controllers\MailController;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

// ================================================================
// Mail API Routes (replaces Java MailService microservice)
// ================================================================
Route::prefix('api/mail')->group(function () {
    Route::post('/import-csv', [MailController::class, 'importCsv']);
    Route::post('/send-single', [MailController::class, 'sendSingle']);
    Route::post('/send-multiple', [MailController::class, 'sendMultiple']);
});

// Reportes - Descargar (existing route)
Route::get('/reportes/descargar/{report}', function (\App\Models\GeneratedReport $report) {
    $service = new \App\Services\ReportService();
    return $service->descargar($report);
})->middleware('auth')->name('reportes.descargar');

require __DIR__.'/settings.php';



#
use App\Http\Controllers\OnboardingController;

Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
Route::get('/onboarding/success', [OnboardingController::class, 'success'])->name('onboarding.success');


