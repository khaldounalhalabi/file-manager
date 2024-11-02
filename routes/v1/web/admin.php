<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;


Route::inertia('/', 'dashboard/Index')->name('index');

Route::put('/update-user-data', [v1\AdminAuthController::class, 'updateUserData'])->name('update.user.data');
Route::get('/user-details', [v1\AdminAuthController::class, 'userDetails'])->name('user.details');
Route::get('/logout', [v1\AdminAuthController::class, 'logout'])->name('logout');

Route::get('users/customers', [v1\UserController::class, 'getCustomers'])->name('users.customers');
Route::get('/groups/{groupId}/users', [v1\UserController::class, 'getUsersByGroup'])->name('groups.users');
Route::get('/users/data', [v1\UserController::class, 'data'])->name('users.data');
Route::post('/users/export', [v1\UserController::class, 'export'])->name('users.export');
Route::Resource('/users', v1\UserController::class)->names('users');

Route::get('/groups/data', [v1\GroupController::class, 'data'])->name('groups.data');
Route::post('/groups/export', [v1\GroupController::class, 'export'])->name('groups.export');
Route::Resource('/groups', v1\GroupController::class)->names('groups');
