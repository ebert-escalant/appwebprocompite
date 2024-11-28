<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
	Route::match(['get', 'post'], 'signin', [AuthController::class, 'signin'])->name('login');
});

Route::get('consultas', [PublicController::class, 'queries'])->name('public.queries');
Route::get('consultas/{dni}', [PublicController::class, 'findByDni'])->name('public.findByDni');

Route::middleware(['auth'])->group(function () {
	Route::match(['get', 'put'], 'profile', [AuthController::class, 'profile'])->name('profile');
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

	// ubigeo
	Route::get('ubigeo/departments', [UbigeoController::class, 'getDepartments'])->name('ubigeo.departments');
	Route::get('ubigeo/provinces/{departement}', [UbigeoController::class, 'getProvinces'])->name('ubigeo.provinces');
	Route::get('ubigeo/districts/{province}', [UbigeoController::class, 'getDistricts'])->name('ubigeo.districts');
	Route::match(['get', 'post'], 'ubigeo/add-province', [UbigeoController::class, 'addProvince'])->name('ubigeo.add-province');
	Route::match(['get', 'post'], 'ubigeo/add-district', [UbigeoController::class, 'addDistrict'])->name('ubigeo.add-district');

	// societies
	Route::get('societies', [SocietyController::class, 'getAll'])->name('societies.index');
	Route::match(['get', 'post'], 'societies/insert', [SocietyController::class, 'insert'])->name('societies.insert');
	Route::match(['get', 'put'], 'societies/edit/{id}', [SocietyController::class, 'edit'])->name('societies.edit');
	Route::delete('societies/delete/{id}', [SocietyController::class, 'delete'])->name('societies.delete');
	Route::get('societies/members/{id}', [SocietyController::class, 'getMembers'])->name('societies.members');
	Route::post('societies/add-member/{id}', [SocietyController::class, 'addMember'])->name('societies.addmember');
	Route::delete('societies/delete-member/{id}', [SocietyController::class, 'deleteMember'])->name('societies.deletemember');
	Route::match(['get', 'put'], 'societies/edit-member-assets/{id}', [SocietyController::class, 'editMemberAssets'])->name('societies.editmemberassets');
	Route::get('societies/projects/{id}', [SocietyController::class, 'getProjects'])->name('societies.projects');
	Route::post('societies/add-project/{id}', [SocietyController::class, 'addProject'])->name('societies.addproject');
	Route::delete('societies/delete-project/{id}', [SocietyController::class, 'deleteProject'])->name('societies.deleteproject');
	Route::match(['get', 'put'], 'societies/edit-project-assets/{id}', [SocietyController::class, 'editProjectAssets'])->name('societies.editprojectassets');
	Route::match(['get', 'put'], 'societies/edit-project-all/{id}', [SocietyController::class, 'editProjectAll'])->name('societies.editprojectall');
	Route::get('societies/download-project-file/{id}/{filename}', [SocietyController::class, 'downloadProjectFile'])->name('societies.downloadprojectfile');

	// projects
	Route::get('projects', [ProjectController::class, 'getAll'])->name('projects.index');
	Route::match(['get', 'post'], 'projects/insert', [ProjectController::class, 'insert'])->name('projects.insert');
	Route::match(['get', 'put'], 'projects/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
	Route::delete('projects/delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');

	Route::get('partners', [PartnerController::class, 'getAll'])->name('partners.index');
	Route::match(['get', 'post'], 'partners/insert', [PartnerController::class, 'insert'])->name('partners.insert');
	Route::match(['get', 'put'], 'partners/edit/{id}', [PartnerController::class, 'edit'])->name('partners.edit');
	Route::delete('partners/delete/{id}', [PartnerController::class, 'delete'])->name('partners.delete');
	Route::get('partners/get-by-dni/{dni}', [PartnerController::class, 'getByDni'])->name('partners.findByDni');

});