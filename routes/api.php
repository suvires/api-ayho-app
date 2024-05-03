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
            Route::get('/user', [AuthController::class, 'meUser'])->name('api.auth.me');
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

        Route::post('offer/create', 'App\Http\Controllers\OfferController@create');
        Route::post('offer/update/{id}', 'App\Http\Controllers\OfferController@update');
        Route::post('offer/like', 'App\Http\Controllers\OfferController@like');
        Route::post('offer/delete/{id}', 'App\Http\Controllers\OfferController@delete');
        Route::post('offer/unpublish', 'App\Http\Controllers\OfferController@unpublish');
        Route::post('offer/publish', 'App\Http\Controllers\OfferController@publish');
        Route::post('offer/dislike', 'App\Http\Controllers\OfferController@dislike');
        Route::post('offer/undo', 'App\Http\Controllers\OfferController@undo');
        Route::get('get-offers', 'App\Http\Controllers\OfferController@getOffers');
        Route::get('get-matches', 'App\Http\Controllers\OfferController@getMatches');

        Route::get('chats/user', 'App\Http\Controllers\ChatController@get_user_chats');
        Route::get('chat/user/{id}', 'App\Http\Controllers\ChatController@get_user_chat_messages');
        Route::post('chat/send-message/{id}', 'App\Http\Controllers\ChatController@send_message');

        Route::get('get-match/{id}', 'App\Http\Controllers\OfferController@getMatch');

        Route::get('offers/mycompany', 'App\Http\Controllers\OfferController@getMyCompanyOffers');
        Route::get('offer/mycompany/{id}', 'App\Http\Controllers\OfferController@getMyCompanyOffer');

        Route::post('company/create', 'App\Http\Controllers\CompanyController@create');
        Route::post('company/update', 'App\Http\Controllers\CompanyController@update');

        Route::post('create-profile', 'App\Http\Controllers\AuthController@createProfile');
        Route::post('update-profile', 'App\Http\Controllers\AuthController@updateProfile');
    });
});

Route::redirect('/', 'https://ayho.app');