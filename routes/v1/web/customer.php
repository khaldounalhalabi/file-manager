<?php

use App\Http\Controllers\WEB\v1;
use App\Http\Middleware\CustomerMustHaveAGroup;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'dashboard/customer/Index')->name('index');

Route::put('/update-user-data', [v1\CustomerAuthController::class, 'updateUserData'])->name('update.user.data');
Route::get('/user-details', [v1\CustomerAuthController::class, 'userDetails'])->name('user.details');
Route::get('/logout', [v1\CustomerAuthController::class, 'logout'])->name('logout');

Route::get('notifications', [v1\NotificationController::class, 'getUserNotification'])->name('notifications');
Route::get('notifications/unread/count', [v1\NotificationController::class, 'unreadCount'])->name('notifications.unread.count');
Route::get('notifications/{notificationId}/mark-as-read', [v1\NotificationController::class, 'markAsRead'])->name('notifications.mark.as.read');

Route::get('users/customers', [v1\UserController::class, 'getCustomers'])->name('users.customers');

Route::get('/groups/{groupId}/users', [v1\UserController::class, 'getUsersByGroup'])
    ->withoutMiddleware(CustomerMustHaveAGroup::class)
    ->name('groups.users');

Route::controller(v1\GroupController::class)
    ->withoutMiddleware(CustomerMustHaveAGroup::class)
    ->group(function () {
        Route::post('/groups/invite', 'invite')->name('groups.invite');
        Route::get('/groups/{groupId}/change', 'changeUserGroup')->name('groups.change');
        Route::get('/groups/{groupId}/select', 'selectGroup')->name('groups.select');
        Route::get('/user/groups', 'userGroups')->name('user.groups');
        Route::get('/groups/data', 'data')->name('groups.data');
        Route::post('/groups/export', 'export')->name('groups.export');
    });
Route::resource('/groups', v1\GroupController::class)
    ->withoutMiddleware(CustomerMustHaveAGroup::class)
    ->names('groups');

Route::inertia('/directories/root', 'dashboard/customer/directories/Index')->name('directories.root');
Route::controller(v1\DirectoryController::class)
    ->name('directories.')
    ->prefix('directories')
    ->group(function () {
        Route::get('/get-root', 'getRoot')->name('get.root');
        Route::post('/directories', 'store')->name('store');
        Route::put('/{directoryId}', 'update')->name('update');
        Route::delete('/{directoryId}', 'destroy')->name('destroy');
        Route::get('/{directoryId}', 'show')->name('show');
    });

Route::get('/files/{fileId}/last-comparison}', [v1\FileController::class, 'getLastComparison'])->name('files.last.comparison');
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

