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
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Root → Login
Route::redirect('/', '/login');

// Login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | STATIC PAGES
    |--------------------------------------------------------------------------
    */

    Route::view('/barangay-update', 'pages.barangay')->name('barangay.update');
    Route::view('/read-message', 'pages.message')->name('read.message');
    Route::view('/worker-info', 'pages.worker')->name('worker.info');


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE + ADMIN
    |--------------------------------------------------------------------------
    */

    // Certificates
    Route::controller(CertificateController::class)->group(function () {
        Route::get('/certificate', 'index')->name('certificate.index');
        Route::post('/certificate', 'store')->name('certificate.store');
        Route::get('/certificate/print/{id}', 'print')->name('certificate.print');
    });

    // Clearance
    Route::controller(ClearanceController::class)->group(function () {
        Route::get('/clearance', 'index')->name('clearance.index');
        Route::post('/clearance', 'store')->name('clearance.store');
        Route::get('/clearance/print/{id}', 'print')->name('clearance.print');
    });

    // Families (EMPLOYEE + ADMIN CAN ACCESS)
    Route::resource('families', FamilyController::class);


    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->group(function () {

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/employee', [SettingsController::class, 'storeEmployee'])->name('settings.employee.store');
        Route::delete('/settings/employee/{id}', [SettingsController::class, 'destroyEmployee'])->name('settings.employee.destroy');
        Route::post('/settings/theme', [SettingsController::class, 'setTheme'])->name('settings.theme');

        // Households
        Route::resource('households', HouseholdController::class);
        Route::get('/households/map', [HouseholdController::class, 'map'])->name('households.map');

        // Residents
        Route::resource('residents', ResidentController::class);
        Route::get('/resident-location', [ResidentController::class, 'location'])->name('residents.location');

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');

        // Workers / Employees
        Route::resource('workers', WorkerController::class);

        // Certificate Admin Actions
        Route::get('/certificate/{id}/edit', [CertificateController::class, 'edit'])->name('certificate.edit');
        Route::put('/certificate/{id}', [CertificateController::class, 'update'])->name('certificate.update');
        Route::delete('/certificate/{id}', [CertificateController::class, 'destroy'])->name('certificate.destroy');

        // Clearance Delete
        Route::delete('/clearance/{id}', [ClearanceController::class, 'destroy'])->name('clearance.destroy');

    });


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/clearance/{id}/edit', [ClearanceController::class, 'edit'])->name('clearance.edit');

Route::put('/clearance/{id}', [ClearanceController::class, 'update'])->name('clearance.update');
});