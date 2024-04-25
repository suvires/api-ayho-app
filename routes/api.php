<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('api')->group(function () {
    // Authentication routes for JWT Authentication In Laravel 11
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'loginUser'])->name('api.auth.login');
        Route::post('/register', [AuthController::class, 'registerUser'])->name('api.auth.register');

        // Protected routes
        Route::middleware('auth:api')->group(function () {
            Route::post('/logout', [AuthController::class, 'logoutUser'])->name('api.auth.logout');
            Route::post('/refresh', [AuthController::class, 'refreshUser'])->name('api.auth.refresh');
            Route::post('/me', [AuthController::class, 'meUser'])->name('api.auth.me');
        });
    });
});

Route::redirect('/', 'https://ayho.app');