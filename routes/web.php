<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Root → Login
Route::redirect('/', '/login');

// Login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.submit')->middleware('throttle:10,1');

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

    /*
        |--------------------------------------------------------------------------
        | EMPLOYEE + ADMIN
        |--------------------------------------------------------------------------
        */

    // Certificates (EMPLOYEE + ADMIN)
    Route::controller(CertificateController::class)->group(function () {
        Route::get('/certificate', 'index')->name('certificate.index');
        Route::post('/certificate', 'store')->name('certificate.store');
        Route::get('/certificate/print/{id}', 'print')->name('certificate.print');
        Route::get('/certificate/{id}/edit', 'edit')->name('certificate.edit');
        Route::put('/certificate/{id}', 'update')->name('certificate.update');
    });

    // Clearance (EMPLOYEE + ADMIN)
    Route::controller(ClearanceController::class)->group(function () {
        Route::get('/clearance', 'index')->name('clearance.index');
        Route::post('/clearance', 'store')->name('clearance.store');
        Route::get('/clearance/print/{id}', 'print')->name('clearance.print');
        Route::get('/clearance/{id}/edit', 'edit')->name('clearance.edit');
        Route::put('/clearance/{id}', 'update')->name('clearance.update');
    });

    // Families (EMPLOYEE + ADMIN)
    Route::resource('families', FamilyController::class);

    // Households (EMPLOYEE + ADMIN — delete is admin-only below)
    Route::get('/households/map', [HouseholdController::class, 'map'])->name('households.map');
    Route::get('/households/search', [HouseholdController::class, 'search'])->name('households.search');
    Route::resource('households', HouseholdController::class)->except(['destroy']);

    // Resident edit & update — employees submit for review, admins apply directly
    Route::get('/residents/{resident}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::put('/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
    Route::patch('/residents/{resident}', [ResidentController::class, 'update']);

    // Theme toggle — available to all authenticated users
    Route::post('/settings/theme', [SettingsController::class, 'setTheme'])->name('settings.theme');

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

        // Households — delete only
        Route::delete('/households/{household}', [HouseholdController::class, 'destroy'])->name('households.destroy');

        // Residents (edit/update handled outside admin for employee access)
        Route::resource('residents', ResidentController::class)->except(['edit', 'update']);
        Route::get('/resident-location', [ResidentController::class, 'location'])->name('residents.location');
        Route::get('/residents-import', [ResidentController::class, 'importForm'])->name('residents.import.form');
        Route::post('/residents-import', [ResidentController::class, 'import'])->name('residents.import');
        Route::post('/residents/{id}/approve', [ResidentController::class, 'approve'])->name('residents.approve');
        Route::post('/residents/{id}/reject', [ResidentController::class, 'reject'])->name('residents.reject');
        Route::post('/residents/edits/{id}/approve', [ResidentController::class, 'approveEdit'])->name('residents.approveEdit');
        Route::post('/residents/edits/{id}/reject', [ResidentController::class, 'rejectEdit'])->name('residents.rejectEdit');

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');

        // Workers / Employees
        Route::resource('workers', WorkerController::class);

        // Certificate & Clearance — delete is admin-only
        Route::delete('/certificate/{id}', [CertificateController::class, 'destroy'])->name('certificate.destroy');
        Route::delete('/clearance/{id}', [ClearanceController::class, 'destroy'])->name('clearance.destroy');

        // Audit Log
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit.index');
        Route::delete('/audit-log/clear', [AuditLogController::class, 'clear'])->name('audit.clear');

    });

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
