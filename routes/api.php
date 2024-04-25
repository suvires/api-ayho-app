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

    Route::middleware('auth:api')->group(function () {
        Route::post('offers', 'App\Http\Controllers\OfferController@index');
        Route::post('companies', 'App\Http\Controllers\CompanyController@index');
        Route::post('skills', 'App\Http\Controllers\SkillController@index');
        Route::post('schedules', 'App\Http\Controllers\ScheduleController@index');
        Route::post('places', 'App\Http\Controllers\PlaceController@index');
        Route::post('positions', 'App\Http\Controllers\PositionController@index');
        Route::post('attendances', 'App\Http\Controllers\AttendanceController@index');

        Route::post('offer/like', 'App\Http\Controllers\OfferController@like');
        Route::post('offer/dislike', 'App\Http\Controllers\OfferController@dislike');
        Route::post('offer/undo', 'App\Http\Controllers\OfferController@undo');
        Route::post('get-offers', 'App\Http\Controllers\OfferController@getOffers');
        Route::post('get-matches', 'App\Http\Controllers\OfferController@getMatches');
    });
});

Route::redirect('/', 'https://ayho.app');