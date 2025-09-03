<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\TenantLoginController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\HomepageController;
use App\Http\Controllers\Tenant\RolesPermissionsController;
use App\Http\Controllers\Tenant\AcademicYearController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\GradeController;
use App\Http\Controllers\Tenant\SectionController;
use App\Http\Controllers\Tenant\TimetableController;
use App\Http\Controllers\Tenant\PeriodController;
use App\Http\Controllers\Tenant\SchoolHolidayController;
use App\Http\Controllers\Tenant\CalendarController;


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
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role_id}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{role_id}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role_id}', [RoleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('manage-permissions', [RolesPermissionsController::class, 'index'])
            ->name('index');
            Route::post('manage-permissions', [RolesPermissionsController::class, 'update'])
            ->name('update');
        });

        // Acadamics
        Route::prefix('academic_years')->name('academic_years.')->group(function () {
                Route::get('/', [AcademicYearController::class, 'index'])->name('index');
                Route::get('/create', [AcademicYearController::class, 'create'])->name('create');
                Route::post('/', [AcademicYearController::class, 'store'])->name('store');
                Route::get('/{academic_year}', [AcademicYearController::class, 'show'])->name('show');
                Route::get('/{academic_year_id}/edit', [AcademicYearController::class, 'edit'])->name('edit');
                Route::put('/{academic_year}', [AcademicYearController::class, 'update'])->name('update');
                Route::delete('/{academic_year}', [AcademicYearController::class, 'destroy'])->name('destroy');
            Route::patch('/{academic_year}/toggle', [AcademicYearController::class, 'toggle'])
            ->name('toggle');
        });

        // Grades CRUD
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('', [GradeController::class, 'index'])->name('index');
            Route::get('/create', [GradeController::class, 'create'])->name('create');
            Route::post('', [GradeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [GradeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GradeController::class, 'update'])->name('update');
            Route::delete('/{id}', [GradeController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sections')->name('sections.')->group(function () {
        // Sections CRUD
            Route::get('', [SectionController::class, 'index'])->name('index');
            Route::get('/create', [SectionController::class, 'create'])->name('create');
            Route::post('', [SectionController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SectionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SectionController::class, 'update'])->name('update');
            Route::delete('/{id}', [SectionController::class, 'destroy'])->name('destroy');
        });

        // setperiods
        Route::prefix('timetables')->name('timetables.')->group(function () {
            Route::get('', [TimetableController::class, 'index'])->name('index');
            Route::get('/create', [TimetableController::class, 'create'])->name('create');
            Route::post('', [TimetableController::class, 'store'])->name('store');
            Route::get('/{id}', [TimetableController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [TimetableController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TimetableController::class, 'update'])->name('update');
            Route::delete('/{id}', [TimetableController::class, 'destroy'])->name('destroy');
            Route::post('{timetable}/periods', [TimetableController::class, 'storePeriod'])
                ->name('periods.store');
            Route::delete('{timetable}/periods/{period}', [TimetableController::class, 'destroyPeriod'])
                ->name('periods.destroy');

            //periods
            Route::post('{timetable}/periods', [PeriodController::class, 'store'])->name('periods.store');
            Route::delete('{timetable}/periods/{period}', [PeriodController::class, 'destroy'])->name('periods.destroy');

            //copy 
            // Copy form + save
            Route::get('copy/form', [TimetableController::class, 'copyForm'])->name('copyForm');
            Route::post('copy/save', [TimetableController::class, 'copySave'])->name('copySave');
            // Periods API (for JS to load rows)
            Route::get('api/{timetable}/periods', [PeriodController::class, 'apiList'])->name('periods.api');
        });

    Route::prefix('school-holidays')->name('school_holidays.')->group(function () {
        Route::get('/', [SchoolHolidayController::class, 'listByAcademic'])->name('index');          // ✅ Blade Calendar View
        Route::get('/list', [SchoolHolidayController::class, 'list'])->name('list');        // JSON
        Route::get('/calendar', [SchoolHolidayController::class, 'calendar'])->name('calendar'); // JSON for FullCalendar
        Route::get('/create', [SchoolHolidayController::class, 'create'])->name('create');  // JSON
        Route::post('/store', [SchoolHolidayController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SchoolHolidayController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [SchoolHolidayController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [SchoolHolidayController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');          // ✅ Blade Calendar View
    });
});
    