<?php
use App\Http\Controllers\WEB\v1;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//add-your-routes-here
Route::get('/v1/dashboard/admin/users/data', [v1\UserController::class, 'data'])->name('v1.web.admin.users.data');
Route::post('/v1/dashboard/admin/users/export' , [v1\UserController::class , 'export'])->name('v1.web.admin.users.export');
Route::get('/v1/dashboard/admin/users/get-import-example', [v1\UserController::class, 'getImportExample'])->name('v1.web.admin.users.get.example');
Route::post('/v1/dashboard/admin/users/import', [v1\UserController::class, 'import'])->name('v1.web.admin.users.import');
Route::Resource('/v1/dashboard/admin/users' , v1\UserController::class)->names('v1.web.admin.users') ;