<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
	Route::match(['get', 'post'], 'signin', [AuthController::class, 'signin'])->name('login');
});
Route::middleware(['auth'])->group(function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

	// ubigeo
	Route::get('ubigeo/departments', [UbigeoController::class, 'getDepartments'])->name('ubigeo.departments');
	Route::get('ubigeo/provinces/{departement}', [UbigeoController::class, 'getProvinces'])->name('ubigeo.provinces');
	Route::get('ubigeo/districts/{province}', [UbigeoController::class, 'getDistricts'])->name('ubigeo.districts');

	// societies
	Route::get('societies', [SocietyController::class, 'getAll'])->name('societies.index');
	Route::match(['get', 'post'], 'societies/insert', [SocietyController::class, 'insert'])->name('societies.insert');
	Route::match(['get', 'put'], 'societies/edit/{id}', [SocietyController::class, 'edit'])->name('societies.edit');
	Route::delete('societies/delete/{id}', [SocietyController::class, 'delete'])->name('societies.delete');

	// projects
	Route::get('projects', [ProjectController::class, 'getAll'])->name('projects.index');
	Route::match(['get', 'post'], 'projects/insert', [ProjectController::class, 'insert'])->name('projects.insert');
	Route::match(['get', 'put'], 'projects/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
	Route::delete('projects/delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');
});