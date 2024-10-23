<?php

use App\Http\Controllers\SetLocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/locale', [SetLocaleController::class, 'setLanguage'])->middleware('web')->withoutMiddleware([App\Http\Middleware\AcceptedLanguagesMiddleware::class])->name('set-locale');
