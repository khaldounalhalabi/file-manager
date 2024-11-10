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
Route::get('/groups/{groupId}/change', [v1\GroupController::class, 'changeUserGroup'])->name('groups.change');
Route::resource('/groups', v1\GroupController::class)
    ->withoutMiddleware(CustomerMustHaveAGroup::class)
    ->names('groups');

Route::get('/directories/get-root', [v1\DirectoryController::class, 'getRoot'])->name('directories.get.root');
Route::inertia('/directories/root', 'dashboard/customer/directories/Index')->name('directories.root');
Route::post('/directories', [v1\DirectoryController::class, 'store'])->name('directories.store');
Route::put('directories/{directoryId}', [v1\DirectoryController::class, 'update'])->name('directories.update');
Route::delete('directories/{directoryId}', [v1\DirectoryController::class, 'destroy'])->name('directories.destroy');
Route::get('directories/{directoryId}', [v1\DirectoryController::class, 'show'])->name('directories.show');

Route::post('/files', [v1\FileController::class, 'store'])->name('files.store');
Route::get('/files/{fileId}/edit', [v1\FileController::class, 'edit'])->name('files.edit');
Route::put('/files/update', [v1\FileController::class, 'pushUpdates'])->name('files.update');
Route::delete('files/{fileId}', [v1\FileController::class, 'destroy'])->name('files.destroy');
