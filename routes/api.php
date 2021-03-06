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

Route::prefix('sign')->namespace('Api')->group(function () {
    Route::post('/', 'SignController@sign');
    Route::post('/re', 'SignController@reSign');
    Route::get('/list', 'SignController@signList');
});

Route::prefix('wechat')->namespace('Api')->group(function () {
    Route::get('/sign', 'WechatController@sign');
});