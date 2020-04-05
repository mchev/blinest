<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'GameController@index');


// STATIQUES
Route::get('/contact', 'ContactController@index');
Route::post('/send', 'ContactController@send');
Route::get('/mentions-legales', 'ContactController@legal');
Route::get('/sitemap.xml', 'SitemapController@index');
Route::get('/politique-confidentialite', 'ContactController@confidentialite');


Route::get('/releases', 'SpotifyController@releases')->name('releases');

Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');

Auth::routes();

//dd(Auth::user(), Auth::Guest());

Route::group(['middleware' => ['auth']], function () {

	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::get('/profile/delete', 'ProfileController@destroy')->name('profile.delete');
	Route::get('/profile/stats/games', 'ProfileController@gameStats');
	Route::post('/profile/update', 'ProfileController@update')->name('profile.update');

	Route::get('/games/me', 'GameController@me')->name('games.me');

	Route::get('/games/{game}/multiplayer', 'GameController@multiplayer')->name('games.multiplayer');
	Route::post('/games/{game}/score', 'GameController@score');
	//Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/stream', 'StreamController@index');
	Route::post('/stream', 'StreamController@track');

});


Route::get('/games/{game}/private', 'GameController@private')->name('games.private');
Route::get('/games/{game}/tracks/search', 'TrackController@search');
Route::post('/games/{game}/random/track', 'GameController@track');
Route::post('/games/{game}/update/score', 'GameController@updateScore')->name('games.update.score');
Route::get('/games/{game}/podium', 'GameController@podium');
Route::get('/games/{game}/tracks/delete', 'GameController@deleteTracks');

Route::get('/parties/{slug}', 'GameController@slug');
Route::resource('/games', 'GameController');

Route::get('/profils/{name}', 'UserController@show');

Route::resource('/games/{game}/tracks', 'TrackController');

Route::post('/tracks/{track}/save/custom/awnser', 'TrackController@updateCustomAnwser');
Route::post('/tracks/{track}/rate/up', 'TrackController@rateUp');
Route::post('/tracks/{track}/rate/down', 'TrackController@rateDown');


Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function() {

	Route::get('/', 'DashboardController@index')->name('dashboard.index');
	Route::post('/', 'DashboardController@index')->name('post.dashboard.index');

	Route::get('users/table', 'UserController@table')->name('users.table');
	Route::resource('users', 'UserController');

	Route::get('tracks/duplicates', 'TrackController@duplicates')->name('tracks.duplicates');
	Route::get('tracks/list', 'TrackController@list')->name('tracks.list');
	Route::resource('tracks', 'TrackController');

	Route::resource('games', 'GameController');

});