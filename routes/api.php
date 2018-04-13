<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function () {

    Route::prefix('sign')->group(function () {
        Route::post('/', 'SignController@sign');
        Route::post('/re', 'SignController@reSign');
        Route::get('/list', 'SignController@signList');
    });

    Route::prefix('user')->group(function () {
        Route::post('login', 'PassportController@login');
        Route::post('register', 'PassportController@register');
    });

});
