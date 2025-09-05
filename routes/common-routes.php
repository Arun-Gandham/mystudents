<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestFormController;
use App\Http\Controllers\Global\HomePageController;

Route::get('/test-form', [TestFormController::class, 'create'])->name('test.create');
Route::post('/test-form', [TestFormController::class, 'store'])->name('test.store');
Route::get('/', [HomePageController::class, 'index'])->name('global.homepage');

