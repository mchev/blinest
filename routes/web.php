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


// CONTACT FORM
Route::get('/contact', function() {
	return view('contact');
});
Route::post('/send', 'ContactController@send');

Route::get('/sitemap.xml', 'SitemapController@index');

Route::get('/mentions-legales', function() {
	return view('legal');
});

Route::get('/politique-confidentialite', function() {
	return view('confidentialite');
});

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

});


Route::get('/games/{game}/private', 'GameController@private')->name('games.private');
Route::get('/games/{game}/tracks/search', 'TrackController@search');
Route::post('/games/{game}/track', 'GameController@track');
Route::post('/games/{game}/update/score', 'GameController@updateScore')->name('games.update.score');
Route::get('/games/{game}/podium', 'GameController@podium');
Route::get('/games/{game}/tracks/delete', 'GameController@deleteTracks');

Route::get('/parties/{slug}', 'GameController@slug');
Route::resource('/games', 'GameController');

Route::get('/profils/{name}', 'UserController@show');

Route::resource('/games/{game}/tracks', 'TrackController');
Route::resource('/games/{game}/rounds', 'RoundController');

Route::post('/tracks/{track}/save/custom/awnser', 'TrackController@updateCustomAnwser');
Route::post('/tracks/{track}/updatetrackrate', 'TrackController@updateTrackRate');


Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function() {

	Route::get('/', 'DashboardController@index')->name('dashboard.index');
	Route::post('/', 'DashboardController@index')->name('dashboard.index');

	Route::get('users/table', 'UserController@table')->name('users.table');
	Route::resource('users', 'UserController');

	Route::get('tracks/duplicates', 'TrackController@duplicates')->name('tracks.duplicates');
	Route::resource('tracks', 'TrackController');

	Route::resource('games', 'GameController');

});