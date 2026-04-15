<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('mail')->middleware('auth:sanctum')->group(function () {
        Route::post('/import-csv', [MailController::class, 'importCsv']);
        Route::post('/send-single', [MailController::class, 'sendSingle']);
        Route::post('/send-multiple', [MailController::class, 'sendMultiple']);
    });
});
