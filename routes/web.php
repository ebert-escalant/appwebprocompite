<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
	Route::match(['get', 'post'], 'signin', [AuthController::class, 'signin'])->name('login');
});
Route::middleware(['auth'])->group(function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
});