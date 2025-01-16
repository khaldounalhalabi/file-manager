<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/admin')
    ->name('v1.web.public.admin.')
    ->group(function () {
        Route::post('login', [v1\AdminAuthController::class, 'login'])->name('login');
        Route::post('request-reset-password-code', [v1\AdminAuthController::class, 'requestResetPasswordCode'])->name('request.reset.password.code');
        Route::post('validate-reset-password-code', [v1\AdminAuthController::class, 'validateResetPasswordCode'])->name('validate.reset.password.code');
        Route::post('change-password', [v1\AdminAuthController::class, 'changePassword'])->name('change.password');

        Route::inertia('login', 'auth/admin/Login')->name('login.page');
        Route::inertia('request-reset-password-code-page', 'auth/admin/ForgetPassword')->name('request.reset.password.code-page');
        Route::inertia('reset-page', 'auth/admin/ResetPassword')->name('reset.password.page');

    });

Route::prefix('v1/customer')
    ->name('v1.web.public.customer.')
    ->group(function () {
        Route::post('login', [v1\CustomerAuthController::class, 'login'])->name('login');
        Route::post('register', [v1\CustomerAuthController::class, 'register'])->name('register');
        Route::post('request-reset-password-code', [v1\CustomerAuthController::class, 'requestResetPasswordCode'])->name('request.reset.password.code');
        Route::post('validate-reset-password-code', [v1\CustomerAuthController::class, 'validateResetPasswordCode'])->name('validate.reset.password.code');
        Route::post('change-password', [v1\CustomerAuthController::class, 'changePassword'])->name('change.password');

        Route::inertia('login', 'auth/customer/Login')->name('login.page');
        Route::inertia('request-reset-password-code-page', 'auth/customer/ForgetPassword')->name('request.reset.password.code-page');
        Route::inertia('reset-page', 'auth/customer/ResetPassword')->name('reset.password.page');
        Route::inertia('/register', 'auth/customer/Register')->name('register.page');
        Route::get('accept-invitation', [v1\UserController::class, 'acceptGroupInvitation'])->name('accept.invitation');
    });

Route::get('fcm/get-token', [v1\UserController::class, 'getFcmToken'])->name('fcm.get.token');
Route::post('fcm/store-token', [v1\UserController::class, 'storeFcmToken'])->name('fcm.store.token');
