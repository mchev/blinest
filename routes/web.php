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

	Route::patch('/tracks/{track}', 'TrackController@update');


	Route::get('/stream', 'StreamController@index');
	Route::post('/stream', 'StreamController@track');

	Route::resource('/lab', 'LabController');
	Route::get('/lab/{lab}/vote', 'LabController@vote');


});


Route::get('/games/{game}/private', 'GameController@private')->name('games.private');
Route::get('/games/{game}/tracks/search', 'TrackController@search');
Route::post('/games/{game}/random/track', 'GameController@track');
Route::post('/games/{game}/update/score', 'GameController@updateScore')->name('games.update.score');
Route::get('/games/{game}/podium', 'GameController@podium');
Route::get('/games/{game}/tracks/delete', 'GameController@deleteTracks');

Route::get('/parties/{slug}', 'GameController@slug');
Route::get('/parties/{slug}/test', 'GameController@test');
Route::resource('/games', 'GameController');


// CUSTOM GAMES
Route::get('/partie/privee/{game}/test', 'CustomGameController@test')->name('games.custom.test');

Route::get('/partie/privee/{game}', 'CustomGameController@index')->name('games.custom.index');
Route::post('/partie/privee/{game}', 'CustomGameController@index')->name('games.custom.password.check');
Route::post('/partie/privee/{game}/fetch', 'CustomGameController@fetch')->name('games.custom.fetch');
Route::get('/partie/privee/play/{track}', 'CustomGameController@play')->name('games.custom.play');
Route::get('/partie/privee/pause/{track}', 'CustomGameController@pause')->name('games.custom.pause');
Route::get('/partie/privee/resume/{track}', 'CustomGameController@resume')->name('games.custom.resume');
Route::get('/partie/privee/{game}/stop', 'CustomGameController@stop')->name('games.custom.stop');
Route::post('/partie/privee/{game}/password', 'CustomGameController@password')->name('games.custom.password');


// GUEST AUTH FOR BROADCASTING
Route::post('/broadcasting/auth', function (Illuminate\Http\Request $request) {

	if (Auth::guest()) {

        if ($request->session()->get('guest_id')) {

        	$guest = [
		    	'id' => $request->session()->get('guest_id'),
		    	'name' => $request->session()->get('guest_name')
	    	];

        } else {

        	$guest = [
		    	'id' => (int) str_replace('.', '', microtime(true)),
		    	'name' => 'anon_' . random_int(100, 999)
	    	];

            $request->session()->put('guest_id', $guest['id']);
            $request->session()->put('guest_name', $guest['name']);
        }

	    $user = new Illuminate\Auth\GenericUser($guest);

	    request()->setUserResolver(function () use ($user) {
	        return $user;
	    });

	}

    return Broadcast::auth(request());

});

// MESSAGES - CHAT
Route::post('/messages', 'Api\MessagesController@index');
Route::post('/messages/send', 'Api\MessagesController@store');


Route::get('/profils/{name}', 'UserController@show');

Route::resource('/games/{game}/tracks', 'TrackController');

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