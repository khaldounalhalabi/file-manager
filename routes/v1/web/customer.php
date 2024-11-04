<?php

use App\Http\Controllers\WEB\v1;
use App\Http\Middleware\CustomerMustHaveAGroup;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'dashboard/customer/Index')->name('index');

Route::put('/update-user-data', [v1\CustomerAuthController::class, 'updateUserData'])->name('update.user.data');
Route::get('/user-details', [v1\CustomerAuthController::class, 'userDetails'])->name('user.details');
Route::get('/logout', [v1\CustomerAuthController::class, 'logout'])->name('logout');

Route::get('/groups/{groupId}/users', [v1\UserController::class, 'getUsersByGroup'])
    ->withoutMiddleware(CustomerMustHaveAGroup::class)
    ->name('groups.users');

Route::controller(v1\GroupController::class)
    ->withoutMiddleware(CustomerMustHaveAGroup::class)
    ->group(function () {
        Route::get('/groups/{groupId}/select', 'selectGroup')
            ->name('groups.select');
        Route::get('/user/groups', 'userGroups')
            ->name('user.groups');
        Route::get('/groups/data', 'data')
            ->name('groups.data');
        Route::post('/groups/export', 'export')
            ->name('groups.export');
    });
Route::resource('/groups', v1\GroupController::class)
    ->withoutMiddleware(CustomerMustHaveAGroup::class)
    ->names('groups');
