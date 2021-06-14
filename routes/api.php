<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [RegisterController::class, 'register'])->name('api.register');
Route::post('/auth/token', [LoginController::class, 'token'])->name('api.login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', [UserController::class, 'me'])->name('auth.me');
});
