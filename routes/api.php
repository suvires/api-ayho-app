<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControllerApi;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('api')->group(function () {
    // Authentication routes for JWT Authentication In Laravel 11
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthControllerApi::class, 'loginUser'])->name('api.auth.login');
        Route::post('/register', [AuthControllerApi::class, 'registerUser'])->name('api.auth.register');
        Route::post('/logout', [AuthControllerApi::class, 'logoutUser'])->name('api.auth.logout');
        Route::post('/refresh', [AuthControllerApi::class, 'refreshUser'])->name('api.auth.refresh');
        Route::post('/me', [AuthControllerApi::class, 'meUser'])->name('api.auth.me');
    });
});