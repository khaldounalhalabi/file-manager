<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;

/** Auth Routes */
Route::post('/v1/dashboard/login', [v1\BaseAuthController::class, 'login'])->name('v1.web.public.login');
Route::post('/v1/dashboard/request-reset-password-code', [v1\BaseAuthController::class, 'requestResetPasswordCode'])->name('v1.web.public.request.reset.password.code');
Route::post('/v1/dashboard/validate-reset-password-code', [v1\BaseAuthController::class, 'validateResetPasswordCode'])->name('v1.web.public.validate.reset.password.code');
Route::post('/v1/dashboard/change-password', [v1\BaseAuthController::class, 'changePassword'])->name('v1.web.public.change.password');
Route::inertia('/v1/dashboard/login', 'auth/Login')->name('v1.web.public.login.page');
Route::inertia('/v1/dashboard/request-reset-password-code-page', 'auth/ForgetPassword')->name('v1.web.public.request.reset.password.code-page');
Route::inertia('/v1/dashboard/reset-page', 'auth/ResetPassword')->name('v1.web.public.reset.password.page');
