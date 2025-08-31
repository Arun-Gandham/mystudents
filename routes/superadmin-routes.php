<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SALoginController;
use App\Http\Controllers\SuperAdmin\SASchoolController;
use App\Http\Controllers\SuperAdmin\SADashboardController;

$root = config('app.tenant_root_domain', 'pocketschool.test');
$superadminPrefix = config('app.superadmin_prefix', 'boom1998');

Route::domain($root)
    ->prefix($superadminPrefix)
    ->name('superadmin.')
    ->group(function () {
        Route::get('/login', [SALoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SALoginController::class, 'login'])->name('login.attempt');
        Route::post('/logout', [SALoginController::class, 'logout'])->name('logout');
        
        Route::middleware(['auth:superadmin'])->group(function () {
            Route::get('/dashboard', [SADashboardController::class, 'dashboard'])->name('dashboard');
            
            Route::prefix('schools')->name('school.')->group(function () {
                Route::resource('', SASchoolController::class); // avoid '/' inside resource
                Route::prefix('{schoolId}')->group(function () {
                    Route::get('/', [SASchoolController::class, 'dashboard'])->name('dashboard');
                    Route::get('/students', [SASchoolController::class, 'students'])->name('students');
                    Route::get('/settings', [SASchoolController::class, 'settings'])->name('settings');
                });
            });
        });

        // Resourse maps
        // HTTPVerb	    URL	                            Name	                    Action
        // GET	        /boom1998/users	                superadmin.users.index	    UserController@index
        // GET	        /boom1998/users/create	        superadmin.users.create	    UserController@create
        // POST	        /boom1998/users	                superadmin.users.store	    UserController@store
        // GET	        /boom1998/users/{user}	        superadmin.users.show	    UserController@show
        // GET	        /boom1998/users/{user}/edit	    superadmin.users.edit	    UserController@edit
        // PUT/PATCH	/boom1998/users/{user}	        superadmin.users.update	    UserController@update
        // DELETE	    /boom1998/users/{user}	        superadmin.users.destroy	UserController@destroy
});