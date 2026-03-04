<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ReportsController;

/*
|--------------------------------------------------------------------------
| AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/

// ROOT → redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// LOGIN PAGE
Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

// LOGIN ACTION
Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('login.submit');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    | DASHBOARD
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | STATIC PAGES
    */
    Route::view('/barangay-update', 'pages.barangay')->name('barangay.update');
    Route::view('/read-message', 'pages.message')->name('read.message');
    Route::view('/worker-info', 'pages.worker')->name('worker.info');

    /*
    | HOUSEHOLD MODULE
    */
    Route::get('/households', [HouseholdController::class, 'index'])
        ->name('households.index');

    Route::get('/households/create', [HouseholdController::class, 'create'])
        ->name('households.create');

    Route::post('/households', [HouseholdController::class, 'store'])
        ->name('households.store');

    Route::get('/households/{id}', [HouseholdController::class, 'show'])
        ->name('households.show');

    Route::get('/households/{id}/edit', [HouseholdController::class, 'edit'])
        ->name('households.edit');

    Route::put('/households/{id}', [HouseholdController::class, 'update'])
        ->name('households.update');

    Route::delete('/households/{id}', [HouseholdController::class, 'destroy'])
        ->name('households.destroy');

    /*
    | RESIDENT MODULE
    */
    Route::get('/residents', [ResidentController::class, 'index'])
        ->name('residents.index');

    Route::get('/residents/create', [ResidentController::class, 'create'])
        ->name('residents.create');

    Route::post('/residents', [ResidentController::class, 'store'])
        ->name('residents.store');
        
    Route::get('/residents/{id}', [ResidentController::class, 'show'])
        ->name('residents.show');

    Route::get('/residents/{id}/edit', [ResidentController::class, 'edit'])
        ->name('residents.edit');

    Route::put('/residents/{id}', [ResidentController::class, 'update'])
        ->name('residents.update');

    Route::delete('/residents/{id}', [ResidentController::class, 'destroy'])
        ->name('residents.destroy');    

    Route::get('/resident-location', [ResidentController::class, 'location'])
        ->name('residents.location');

    /*
    | CERTIFICATE MODULE
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
    | CLEARANCE MODULE
    */
    Route::get('/clearance', [ClearanceController::class, 'index'])
        ->name('clearance.index');

    Route::post('/clearance', [ClearanceController::class, 'store'])
        ->name('clearance.store');

    Route::get('/clearance/print/{id}', [ClearanceController::class, 'print'])
        ->name('clearance.print');

    Route::delete('/clearance/{id}', [ClearanceController::class, 'destroy'])
        ->name('clearance.destroy');

    /*
    | REPORTS MODULE
    */
    Route::get('/reports', [ReportsController::class, 'index'])
        ->name('reports.index');
        
    /*
    | LOGOUT
    */
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

        


        Route::resource('workers', WorkerController::class);
});