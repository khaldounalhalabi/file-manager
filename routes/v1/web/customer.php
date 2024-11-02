<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'dashboard/Index')->name('index');

Route::put('/update-user-data', [v1\CustomerAuthController::class, 'updateUserData'])->name('update.user.data');
Route::get('/user-details', [v1\CustomerAuthController::class, 'userDetails'])->name('user.details');
Route::get('/logout', [v1\CustomerAuthController::class, 'logout'])->name('logout');
