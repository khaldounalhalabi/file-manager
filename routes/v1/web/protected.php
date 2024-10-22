<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;

/** Auth Routes */
Route::put('/v1/dashboard/update-user-data', [v1\BaseAuthController::class, 'updateUserData'])->name('v1.web.protected.update.user.data');
Route::get('/v1/dashboard/user-details', [v1\BaseAuthController::class, 'userDetails'])->name('v1.web.protected.user.details');
Route::get('/v1/dashboard/logout', [v1\BaseAuthController::class, 'logout'])->name('v1.web.protected.logout');
