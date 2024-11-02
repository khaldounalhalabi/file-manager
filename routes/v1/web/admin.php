<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;


Route::inertia('/v1/admin/', 'dashboard/Index')->name('v1.web.admin.index');

Route::put('/v1/admin/update-user-data', [v1\AdminAuthController::class, 'updateUserData'])->name('v1.web.admin.update.user.data');
Route::get('/v1/admin/user-details', [v1\AdminAuthController::class, 'userDetails'])->name('v1.web.admin.user.details');
Route::get('/v1/admin/logout', [v1\AdminAuthController::class, 'logout'])->name('v1.web.admin.logout');

Route::get('/v1/admin/users/data', [v1\UserController::class, 'data'])->name('v1.web.admin.users.data');
Route::post('/v1/admin/users/export', [v1\UserController::class, 'export'])->name('v1.web.admin.users.export');
Route::get('/v1/admin/users/get-import-example', [v1\UserController::class, 'getImportExample'])->name('v1.web.admin.users.get.example');
Route::post('/v1/admin/users/import', [v1\UserController::class, 'import'])->name('v1.web.admin.users.import');
Route::Resource('/v1/admin/users', v1\UserController::class)->names('v1.web.admin.users');
