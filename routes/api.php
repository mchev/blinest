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


Route::post('register', 'Api\RegisterController@register');

Route::post('/login', 'Api\AuthController@login')->name('login.api');
Route::post('/register', 'Api\AuthController@register')->name('register.api');

Route::resource('/games', 'Api\GameController');


Route::middleware('auth:api')->group( function () {

	Route::get('/user', function (Request $request) {
	    return $request->user();
	});


	// Messages
	Route::post('/messages', 'Api\MessagesController@index');
	Route::post('/messages/send', 'Api\MessagesController@store');

	Route::get('/media/search', 'Api\DeezerController@search');
	Route::get('/deezer/playlist', 'Api\DeezerController@addPlaylist');
	Route::post('/deezer/store/playlist', 'Api\DeezerController@storePlaylist');

});