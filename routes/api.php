<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('api')->group(function () {
    // Authentication routes for JWT Authentication In Laravel 11
    Route::prefix('auth')->group(function () {
        Route::post('/signin', [AuthController::class, 'loginUser'])->name('api.auth.signin');
        Route::post('/signup', [AuthController::class, 'registerUser'])->name('api.auth.register');

        // Protected routes
        Route::middleware('auth:api')->group(function () {
            Route::post('/signout', [AuthController::class, 'logoutUser'])->name('api.auth.signout');
            Route::post('/refresh', [AuthController::class, 'refreshUser'])->name('api.auth.refresh');
            Route::get('/me', [AuthController::class, 'meUser'])->name('api.auth.me');
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('offers', 'App\Http\Controllers\OfferController@index');
        Route::get('companies', 'App\Http\Controllers\CompanyController@index');
        Route::get('skills', 'App\Http\Controllers\SkillController@index');
        Route::get('schedules', 'App\Http\Controllers\ScheduleController@index');
        Route::get('places', 'App\Http\Controllers\PlaceController@index');
        Route::get('positions', 'App\Http\Controllers\PositionController@index');
        Route::get('attendances', 'App\Http\Controllers\AttendanceController@index');

        Route::post('offer/like', 'App\Http\Controllers\OfferController@like');
        Route::post('offer/dislike', 'App\Http\Controllers\OfferController@dislike');
        Route::post('offer/undo', 'App\Http\Controllers\OfferController@undo');
        Route::get('get-offers', 'App\Http\Controllers\OfferController@getOffers');
        Route::get('get-matches', 'App\Http\Controllers\OfferController@getMatches');

        Route::get('get-match/{id}', 'App\Http\Controllers\OfferController@getMatch');

        Route::get('offers/mycompany', 'App\Http\Controllers\OfferController@getMyCompanyOffers');

        Route::post('create-company', 'App\Http\Controllers\CompanyController@create');
        Route::post('create-offer', 'App\Http\Controllers\OfferController@create');

        Route::post('create-profile', 'App\Http\Controllers\AuthController@createProfile');
    });
});

Route::redirect('/', 'https://ayho.app');