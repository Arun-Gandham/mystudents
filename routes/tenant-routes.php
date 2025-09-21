<?php

use App\Http\Controllers\Tenant\AcademicYearController;
use App\Http\Controllers\Tenant\CalendarController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\Exam\ExamController;
use App\Http\Controllers\Tenant\Exam\ExamResultController;
use App\Http\Controllers\Tenant\Fee\FeeHeadController;
use App\Http\Controllers\Tenant\Fee\FeeReceiptController;
use App\Http\Controllers\Tenant\Fee\SectionFeeController;
use App\Http\Controllers\Tenant\Fee\StudentFeeItemController;
use App\Http\Controllers\Tenant\GradeController;
use App\Http\Controllers\Tenant\HomepageController;
use App\Http\Controllers\Tenant\PeriodController;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\RolesPermissionsController;
use App\Http\Controllers\Tenant\SchoolHolidayController;
use App\Http\Controllers\Tenant\SearchController;
use App\Http\Controllers\Tenant\SectionController;
use App\Http\Controllers\Tenant\StaffController;
use App\Http\Controllers\Tenant\Staff\StaffAttendanceController;
use App\Http\Controllers\Tenant\Student\StudentAddressController;
use App\Http\Controllers\Tenant\Student\StudentAdmissionController;
use App\Http\Controllers\Tenant\Student\StudentApplicationController;
use App\Http\Controllers\Tenant\Student\StudentAttendanceController;
use App\Http\Controllers\Tenant\Student\StudentController;
use App\Http\Controllers\Tenant\Student\StudentDocumentController;
use App\Http\Controllers\Tenant\Student\StudentGuardianController;
use App\Http\Controllers\Tenant\SubjectController;
use App\Http\Controllers\Tenant\SystemSettingsController;
use App\Http\Controllers\Tenant\TenantLoginController;
use App\Http\Controllers\Tenant\TimetableController;
use Illuminate\Support\Facades\Route;

$root = config('app.tenant_root_domain', 'pocketschool.test');

/* ---------- Tenant (wildcard subdomains) ---------- */
Route::domain('{school_sub}.' . $root)
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
            Route::get('/search', [SearchController::class, 'index'])->name('search');

            // System Settings
            Route::prefix('settings/system')->name('settings.system.')->group(function () {
                Route::get('/', [SystemSettingsController::class, 'edit'])->name('edit');
                Route::put('/', [SystemSettingsController::class, 'update'])->name('update');
            });

            // Roles & Permissions
            Route::prefix('roles')->middleware(['module.enabled:roles_permissions'])->name('roles.')->group(function () {
                Route::get('/', [RoleController::class, 'index'])->name('index');
                Route::get('/create', [RoleController::class, 'create'])->name('create');
                Route::post('/', [RoleController::class, 'store'])->name('store');
                Route::get('/{role_id}/edit', [RoleController::class, 'edit'])->name('edit');
                Route::put('/{role_id}', [RoleController::class, 'update'])->name('update');
                Route::delete('/{role_id}', [RoleController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('permissions')->middleware(['module.enabled:roles_permissions'])->name('permissions.')->group(function () {
                Route::get('manage-permissions', [RolesPermissionsController::class, 'index'])
                    ->name('index');
                Route::post('manage-permissions', [RolesPermissionsController::class, 'update'])
                    ->name('update');
            });

            // Academics
            Route::prefix('academic_years')->middleware(['module.enabled:academics'])->name('academic_years.')->group(function () {
                Route::get('/', [AcademicYearController::class, 'index'])->name('index');
                Route::get('/create', [AcademicYearController::class, 'create'])->name('create');
                Route::post('/', [AcademicYearController::class, 'store'])->name('store');
                Route::get('/{academic_year_id}/edit', [AcademicYearController::class, 'edit'])->name('edit');
                Route::put('/{academic_year}', [AcademicYearController::class, 'update'])->name('update');
                Route::patch('/{academic_year}/toggle', [AcademicYearController::class, 'toggle'])
                    ->name('toggle');
            });

            // Grades CRUD
            Route::prefix('grades')->middleware(['module.enabled:grades'])->name('grades.')->group(function () {
                Route::get('', [GradeController::class, 'index'])->name('index');
                Route::get('/create', [GradeController::class, 'create'])->name('create');
                Route::post('', [GradeController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [GradeController::class, 'edit'])->name('edit');
                Route::put('/{id}', [GradeController::class, 'update'])->name('update');
                Route::delete('/{id}', [GradeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('sections')->middleware(['module.enabled:sections'])->name('sections.')->group(function () {
                // Sections CRUD
                Route::get('', [SectionController::class, 'index'])->name('index');
                Route::get('/create', [SectionController::class, 'create'])->name('create');
                Route::post('', [SectionController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [SectionController::class, 'edit'])->name('edit');
                Route::put('/{id}', [SectionController::class, 'update'])->name('update');
                Route::delete('/{id}', [SectionController::class, 'destroy'])->name('destroy');

                Route::get('by-grade', [SectionController::class, 'byGrade'])->name('byGrade');
            });

            // setperiods
            Route::prefix('timetables')->middleware(['module.enabled:timetables'])->name('timetables.')->group(function () {
                Route::get('', [TimetableController::class, 'index'])->name('index');
                Route::get('/create', [TimetableController::class, 'create'])->name('create');
                Route::post('', [TimetableController::class, 'store'])->name('store');
                Route::get('/{id}', [TimetableController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [TimetableController::class, 'edit'])->name('edit');
                Route::put('/{id}', [TimetableController::class, 'update'])->name('update');
                Route::delete('/{id}', [TimetableController::class, 'destroy'])->name('destroy');
                // Periods
                Route::post('{timetable}/periods', [PeriodController::class, 'store'])->name('periods.store');
                Route::delete('{timetable}/periods/{period}', [PeriodController::class, 'destroy'])->name('periods.destroy');

                //copy
                // Copy form + save
                Route::get('copy/form', [TimetableController::class, 'copyForm'])->name('copyForm');
                Route::post('copy/save', [TimetableController::class, 'copySave'])->name('copySave');
                // Periods API (for JS to load rows)
                Route::get('api/{timetable}/periods', [PeriodController::class, 'apiList'])->name('periods.api');
            });

            Route::prefix('school-holidays')->middleware(['module.enabled:holidays'])->name('school_holidays.')->group(function () {
                Route::get('/', [SchoolHolidayController::class, 'listByAcademic'])->name('index');      // ✅ Blade Calendar View
                Route::get('/list', [SchoolHolidayController::class, 'list'])->name('list');             // JSON
                Route::get('/calendar', [SchoolHolidayController::class, 'calendar'])->name('calendar'); // JSON for FullCalendar
                Route::get('/create', [SchoolHolidayController::class, 'create'])->name('create');       // JSON
                Route::post('', [SchoolHolidayController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [SchoolHolidayController::class, 'edit'])->name('edit');
                Route::put('/{id}', [SchoolHolidayController::class, 'update'])->name('update');
                Route::delete('/{id}', [SchoolHolidayController::class, 'destroy'])->name('destroy');
            });

            // calendar
            Route::prefix('calendar')->middleware(['module.enabled:calendar'])->name('calendar.')->group(function () {
                Route::get('/', [CalendarController::class, 'index'])->name('index'); // ✅ Blade Calendar View
            });

            // Subjects
            Route::prefix('subjects')->middleware(['module.enabled:subjects'])->name('subjects.')->group(function () {
                Route::get('/', [SubjectController::class, 'index'])->name('index');
                Route::get('/create', [SubjectController::class, 'create'])->name('create');
                Route::post('', [SubjectController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [SubjectController::class, 'edit'])->name('edit');
                Route::put('/{id}', [SubjectController::class, 'update'])->name('update');
                Route::delete('/{id}', [SubjectController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('staff')->middleware(['module.enabled:staff'])->name('staff.')->group(function () {
                Route::get('/', [StaffController::class, 'index'])->name('index');
                Route::get('/create', [StaffController::class, 'create'])->name('create');
                Route::post('', [StaffController::class, 'store'])->name('store');
                Route::get('/{id}', [StaffController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [StaffController::class, 'edit'])->name('edit');
                Route::put('/{id}', [StaffController::class, 'update'])->name('update');
                Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('', [ProfileController::class, 'show'])
                    ->name('show');
                Route::get('edit', [ProfileController::class, 'edit'])
                    ->name('edit');
                Route::put('', [ProfileController::class, 'update'])
                    ->name('update');
            });

            // ========================
            // 1. Student Applications
            // ========================
            Route::prefix('applications')->middleware(['module.enabled:applications'])->name('applications.')->group(function () {
                Route::get('/', [StudentApplicationController::class, 'index'])->name('index');
                Route::get('/create', [StudentApplicationController::class, 'create'])->name('create');
                Route::post('/', [StudentApplicationController::class, 'store'])->name('store');

                Route::get('/{application}', [StudentApplicationController::class, 'show'])->name('show');
                Route::get('/{application}/edit', [StudentApplicationController::class, 'edit'])->name('edit');
                Route::put('/{application}', [StudentApplicationController::class, 'update'])->name('update');
                Route::delete('/{application}', [StudentApplicationController::class, 'destroy'])->name('destroy');

                Route::post('/{application}/logs', [StudentApplicationController::class, 'addLog'])->name('addLog');

                // Move application → admission
                Route::get('/{application}/admit', [StudentAdmissionController::class, 'createFromApplication'])->name('admit.form');
                Route::post('/{application}/admit', [StudentAdmissionController::class, 'storeFromApplication'])->name('admit.store');
            });

            // ========================
            // 2. Student Admissions
            // ========================
            Route::prefix('admissions')->middleware(['module.enabled:admissions'])->name('admissions.')->group(function () {
                Route::get('/', [StudentAdmissionController::class, 'index'])->name('index');
                Route::get('/create', [StudentAdmissionController::class, 'create'])->name('create');
                Route::post('/', [StudentAdmissionController::class, 'store'])->name('store');

                Route::get('/{admission}/edit', [StudentAdmissionController::class, 'edit'])->name('edit');
                Route::put('/{admission}', [StudentAdmissionController::class, 'update'])->name('update');
                Route::delete('/{admission}', [StudentAdmissionController::class, 'destroy'])->name('destroy');
            });

            // ========================
            // 3. Students
            // ========================
            Route::prefix('students')->middleware(['module.enabled:students'])->name('students.')->group(function () {
                Route::get('/', [StudentController::class, 'index'])->name('index');
                Route::get('/create', [StudentController::class, 'create'])->name('create');
                Route::post('/', [StudentController::class, 'store'])->name('store');

                Route::get('/{id}', [StudentController::class, 'show'])->name('show');
                Route::get('/{id}/overview', [StudentController::class, 'overview'])->name('overview');
                Route::get('/{id}/attendance', [StudentController::class, 'attendance'])->name('attendance');
                Route::get('/{id}/performance', [StudentController::class, 'performance'])->name('performance');
                Route::get('/{id}/behavior', [StudentController::class, 'behavior'])->name('behavior');
                Route::get('/{id}/documents', [StudentController::class, 'documents'])->name('documents');
                Route::get('/{id}/timetable', [StudentController::class, 'timetable'])->name('timetable');
                Route::get('/{id}/guardians', [StudentController::class, 'guardians'])->name('guardians');

                Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
                Route::put('/{student}', [StudentController::class, 'update'])->name('update');
                Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');

                // -------------------------
                // Student Guardians
                // -------------------------
                Route::prefix('{student}/guardians')->name('guardians.')->group(function () {
                    Route::get('/', [StudentGuardianController::class, 'index'])->name('index');
                    Route::get('/create', [StudentGuardianController::class, 'create'])->name('create');
                    Route::post('/', [StudentGuardianController::class, 'store'])->name('store');
                    Route::get('/{guardian}/edit', [StudentGuardianController::class, 'edit'])->name('edit');
                    Route::put('/{guardian}', [StudentGuardianController::class, 'update'])->name('update');
                    Route::delete('/{guardian}', [StudentGuardianController::class, 'destroy'])->name('destroy');
                });

                // -------------------------
                // Student Addresses
                // -------------------------
                Route::prefix('{student}/addresses')->name('addresses.')->group(function () {
                    Route::get('/', [StudentAddressController::class, 'index'])->name('index');
                    Route::get('/create', [StudentAddressController::class, 'create'])->name('create');
                    Route::post('/', [StudentAddressController::class, 'store'])->name('store');
                    Route::get('/{address}/edit', [StudentAddressController::class, 'edit'])->name('edit');
                    Route::put('/{address}', [StudentAddressController::class, 'update'])->name('update');
                    Route::delete('/{address}', [StudentAddressController::class, 'destroy'])->name('destroy');
                });

                // -------------------------
                // Student Documents
                // -------------------------
                Route::prefix('{student}/documents')->name('documents.')->group(function () {
                    Route::get('/', [StudentDocumentController::class, 'index'])->name('index');
                    Route::get('/create', [StudentDocumentController::class, 'create'])->name('create');
                    Route::post('/', [StudentDocumentController::class, 'store'])->name('store');
                    Route::delete('/{document}', [StudentDocumentController::class, 'destroy'])->name('destroy');
                });
            });
            // Staff attendance
            Route::prefix('staff-attendance')->middleware(['module.enabled:attendance_staff'])->name('staffAttendance.')->group(function () {
                Route::get('', [StaffAttendanceController::class, 'index'])->name('index');
                Route::get('create', [StaffAttendanceController::class, 'create'])->name('create');
                Route::post('store', [StaffAttendanceController::class, 'store'])->name('store');
                Route::get('/{attendance}/edit', [StaffAttendanceController::class, 'edit'])->name('edit'); // edit attendance
                Route::put('/{attendance}', [StaffAttendanceController::class, 'update'])->name('update');  // update attendance

                Route::get('/list', [StaffAttendanceController::class, 'list'])->name('list');
            });

            // Student attendance
            Route::prefix('attendance/student')->middleware(['module.enabled:attendance_student'])->name('studentAttendance.')->group(function () {
                Route::get('/', [StudentAttendanceController::class, 'index'])->name('index');
                Route::get('/create', [StudentAttendanceController::class, 'create'])->name('create');
                Route::post('/store', [StudentAttendanceController::class, 'store'])->name('store');
                Route::get('/{sheet}/edit', [StudentAttendanceController::class, 'edit'])->name('edit');
                Route::put('/{sheet}', [StudentAttendanceController::class, 'update'])->name('update');
                Route::get('/{sheet}/view', [StudentAttendanceController::class, 'view'])->name('view');
                Route::post('/copy-morning', [StudentAttendanceController::class, 'copyMorning'])->name('copyMorning');
            });

            Route::prefix('exams')->middleware(['module.enabled:exams'])->name('exams.')->group(function () {
                // Exams CRUD
                Route::get('/', [ExamController::class, 'index'])->name('index');
                Route::get('/create', [ExamController::class, 'create'])->name('create');
                Route::post('/', [ExamController::class, 'store'])->name('store');
                Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
                Route::get('/{exam}/edit', [ExamController::class, 'edit'])->name('edit');
                Route::put('/{exam}', [ExamController::class, 'update'])->name('update');
                Route::delete('/{exam}', [ExamController::class, 'destroy'])->name('destroy');

                // Results entry
                Route::get('/{exam}/results', [ExamResultController::class, 'edit'])->name('results.edit');
                Route::put('/{exam}/results', [ExamResultController::class, 'update'])->name('results.update');

                //Tabs
                Route::get('{exam}/tab/{tab}', [ExamController::class, 'tabContent'])->name('tab');

                Route::put('{exam}/toggle-publish', [ExamController::class, 'togglePublish'])->name('toggle-publish');
            });

            Route::prefix('fees')->middleware(['module.enabled:fees'])->name('fees.')->group(function () {
                Route::prefix('fee-heads')->name('fee-heads.')->group(function () {
                    Route::get('', [FeeHeadController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [FeeHeadController::class, 'create'])
                        ->name('create');

                    Route::post('', [FeeHeadController::class, 'store'])
                        ->name('store');

                    Route::get('/{feeHead}/edit', [FeeHeadController::class, 'edit'])
                        ->name('edit');

                    Route::put('/{feeHead}', [FeeHeadController::class, 'update'])
                        ->name('update');

                    Route::delete('/{feeHead}', [FeeHeadController::class, 'destroy'])
                        ->name('destroy');
                });
                Route::prefix('section-fees')->name('section-fees.')->group(function () {
                    Route::get('', [SectionFeeController::class, 'index'])->name('index');
                    Route::get('/create', [SectionFeeController::class, 'create'])->name('create');
                    Route::post('', [SectionFeeController::class, 'store'])->name('store');
                });
                // bulk assign fees
                Route::post('sections/{sectionId}/academics/{academicId}/assign', [StudentFeeItemController::class, 'bulkAssign'])
                    ->name('fees.bulkAssign');
                Route::prefix('student-fee-items')->name('student-fee-items.')->group(function () {
                    // List all fee items for a student
                    Route::get('students/{student}', [StudentFeeItemController::class, 'index'])
                        ->name('index');

                    // Optional: edit one fee item (apply discount, etc.)
                    Route::get('/{item}/edit', [StudentFeeItemController::class, 'edit'])
                        ->name('edit');
                    Route::put('/{item}', [StudentFeeItemController::class, 'update'])
                        ->name('update');

                    // Optional: delete/reset
                    Route::delete('/{item}', [StudentFeeItemController::class, 'destroy'])
                        ->name('destroy');
                });

                // receipts
                Route::get('receipts', [FeeReceiptController::class, 'allReceipts'])->name('fee-receipts.all');
                Route::get('students/{student}/receipts', [FeeReceiptController::class, 'index'])->name('fee-receipts.index');
                Route::get('students/{student}/receipts/create', [FeeReceiptController::class, 'create'])->name('fee-receipts.create');
                Route::post('students/{student}/receipts', [FeeReceiptController::class, 'store'])->name('fee-receipts.store');
                Route::get('students/{student}/receipts/{receipt}', [FeeReceiptController::class, 'show'])->name('fee-receipts.show');
                Route::get('students/{student}/receipts/{receipt}/edit', [FeeReceiptController::class, 'edit'])->name('fee-receipts.edit');
                Route::put('students/{student}/receipts/{receipt}', [FeeReceiptController::class, 'update'])->name('fee-receipts.update');
            });
        });
    });
