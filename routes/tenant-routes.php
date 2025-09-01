<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\TenantLoginController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\HomepageController;
use App\Http\Controllers\Tenant\RolesPermissionsController;

$root = config('app.tenant_root_domain', 'pocketschool.test');

/* ---------- Tenant (wildcard subdomains) ---------- */
Route::domain('{school_sub}.'.$root)
    ->middleware(['web', 'resolve.school'])
    ->name('tenant.')
    ->group(function () {
        Route::get('/', [HomepageController::class, 'homepage'])->name('homepage');

        // Login
        Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [TenantLoginController::class, 'login'])->name('login.perform');
        Route::post('/logout', [TenantLoginController::class, 'logout'])->name('logout');

        // Protected tenant routes (enforce school match)
        Route::middleware(['auth:tenant', 'user.school.guard'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        });

        // Roles & Permissions
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('manage-permissions', [RolesPermissionsController::class, 'index'])
            ->name('permissions.index');
            Route::post('manage-permissions', [RolesPermissionsController::class, 'update'])
            ->name('permissions.update');
        });
    });
    