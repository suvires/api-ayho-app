<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function() {
    Route::post('signUp', 'App\Http\Controllers\AuthController@signUp');
    Route::post('signIn', 'App\Http\Controllers\AuthController@signIn');

    Route::middleware('auth:sanctum')->group(function() {
        Route::get('getUser', 'App\Http\Controllers\AuthController@getUser');
        Route::get('signOut', 'App\Http\Controllers\AuthController@signOut');
    });
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('offer/like', 'App\Http\Controllers\OfferController@like');
    Route::post('offer/dislike', 'App\Http\Controllers\OfferController@dislike');
    Route::get('offer/undo', 'App\Http\Controllers\OfferController@undo');
    Route::get('getOffers', 'App\Http\Controllers\OfferController@getOffers');
    Route::get('getMatches', 'App\Http\Controllers\OfferController@getMatches');
});

Route::get('offers', 'App\Http\Controllers\OfferController@index');
Route::get('companies', 'App\Http\Controllers\CompanyController@index');
Route::get('skills', 'App\Http\Controllers\SkillController@index');
Route::get('schedules', 'App\Http\Controllers\ScheduleController@index');
Route::get('places', 'App\Http\Controllers\PlaceController@index');
Route::get('positions', 'App\Http\Controllers\PositionController@index');
Route::get('attendances', 'App\Http\Controllers\AttendanceController@index');
