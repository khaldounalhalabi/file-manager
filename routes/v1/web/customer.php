<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;


Route::put('/v1/customer/update-user-data', [v1\CustomerAuthController::class, 'updateUserData'])->name('v1.web.customer.update.user.data');
Route::get('/v1/customer/user-details', [v1\CustomerAuthController::class, 'userDetails'])->name('v1.web.customer.user.details');
Route::get('/v1/customer/logout', [v1\CustomerAuthController::class, 'logout'])->name('v1.web.customer.logout');
