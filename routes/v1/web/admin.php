<?php

use App\Http\Controllers\WEB\v1;
use Illuminate\Support\Facades\Route;


Route::inertia('/', 'dashboard/Index')->name('index');

Route::put('/update-user-data', [v1\AdminAuthController::class, 'updateUserData'])->name('update.user.data');
Route::get('/user-details', [v1\AdminAuthController::class, 'userDetails'])->name('user.details');
Route::get('/logout', [v1\AdminAuthController::class, 'logout'])->name('logout');

Route::get('/users/{userId}/logs', [v1\FileLogController::class, 'getByUser'])->name('users.logs');
Route::get('users/customers', [v1\UserController::class, 'getCustomers'])->name('users.customers');
Route::get('/groups/{groupId}/users', [v1\UserController::class, 'getUsersByGroup'])->name('groups.users');
Route::get('/users/data', [v1\UserController::class, 'data'])->name('users.data');
Route::post('/users/export', [v1\UserController::class, 'export'])->name('users.export');
Route::Resource('/users', v1\UserController::class)->names('users');

Route::get('/groups/{groupId}/directories', [v1\DirectoryController::class, 'getRootPageByGroup'])->name('groups.directories');
Route::get('/groups/{groupId}/directories/root', [v1\DirectoryController::class, 'getRootByGroup'])->name('groups.directories.root');
Route::controller(v1\DirectoryController::class)
    ->name('directories.')
    ->prefix('directories')
    ->group(function () {
        Route::post('/directories', 'store')->name('store');
        Route::put('/{directoryId}', 'update')->name('update');
        Route::delete('/{directoryId}', 'destroy')->name('destroy');
        Route::get('/{directoryId}', 'show')->name('show');
    });

Route::get('/groups/data', [v1\GroupController::class, 'data'])->name('groups.data');
Route::post('/groups/export', [v1\GroupController::class, 'export'])->name('groups.export');
Route::Resource('/groups', v1\GroupController::class)->names('groups');

Route::post('files/{fileId}/logs/export', [v1\FileLogController::class, 'export'])->name('files.logs.export');
Route::get('files/{fileId}/logs', [v1\FileLogController::class, 'getByFile'])->name('files.logs');
Route::post('get-diff', [v1\FileController::class, 'getDiff'])->name('get.diff');
Route::get('stream-file', [v1\FileController::class, 'streamFile'])->name('stream.file');
Route::get('file-versions/{fileId}', [v1\FileVersionController::class, 'getByFile'])->name('files.versions');
Route::controller(v1\FileController::class)
    ->name('files.')
    ->prefix('files')
    ->group(function () {
        Route::post('/', 'store')->name('store');
        Route::post('edit-multiple', 'editMultipleFiles')->name('edit.multiple');
        Route::get('/{fileId}/edit', 'edit')->name('edit');
        Route::put('/update', 'pushUpdates')->name('update');
        Route::delete('/{fileId}', 'destroy')->name('destroy');
        Route::get('/{fileId}', 'show')->name('show');
    });

