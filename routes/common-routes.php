<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestFormController;

Route::get('/test-form', [TestFormController::class, 'create'])->name('test.create');
Route::post('/test-form', [TestFormController::class, 'store'])->name('test.store');

