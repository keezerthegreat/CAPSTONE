<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ClearanceController;

/*
|--------------------------------------------------------------------------
| AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | STATIC PAGES (NO CONTROLLER LOGIC)
    |--------------------------------------------------------------------------
    */
    Route::view('/barangay-update', 'pages.barangay')->name('barangay.update');
    Route::view('/read-message', 'pages.message')->name('read.message');
    Route::view('/worker-info', 'pages.worker')->name('worker.info');

    /*
    |--------------------------------------------------------------------------
    | RESIDENT MODULE
    |--------------------------------------------------------------------------
    */
    Route::get('/residents', [ResidentController::class, 'index'])
        ->name('residents.index');

    Route::get('/residents/create', [ResidentController::class, 'create'])
        ->name('residents.create');

    Route::post('/residents', [ResidentController::class, 'store'])
        ->name('residents.store');

    // 📍 RESIDENT LOCATION (MAP)
    Route::get('/resident-location', [ResidentController::class, 'location'])
        ->name('residents.location');

    /*
    |--------------------------------------------------------------------------
    | CERTIFICATE MODULE (FULL CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/certificate', [CertificateController::class, 'index'])
        ->name('certificate.index');

    Route::post('/certificate', [CertificateController::class, 'store'])
        ->name('certificate.store');

    Route::get('/certificate/print/{id}', [CertificateController::class, 'print'])
        ->name('certificate.print');

    Route::get('/certificate/{id}/edit', [CertificateController::class, 'edit'])
        ->name('certificate.edit');

    Route::put('/certificate/{id}', [CertificateController::class, 'update'])
        ->name('certificate.update');

    Route::delete('/certificate/{id}', [CertificateController::class, 'destroy'])
        ->name('certificate.destroy');

    /*
    |--------------------------------------------------------------------------
    | CLEARANCE MODULE
    |--------------------------------------------------------------------------
    */
    Route::get('/clearance', [ClearanceController::class, 'index'])
        ->name('clearance.index');

    Route::post('/clearance', [ClearanceController::class, 'store'])
        ->name('clearance.store');

    Route::delete('/clearance/{id}', [ClearanceController::class, 'destroy'])
        ->name('clearance.destroy');

    Route::get('/clearance/print/{id}', [ClearanceController::class, 'print'])
        ->name('clearance.print');

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
