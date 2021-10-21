<?php

use Illuminate\Support\Facades\Redis;


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

//Route::group(['middleware' => ['blockip']], function () {

	Route::get('/', 'GameController@index');

	// STATIQUES
	Route::get('/contact', 'ContactController@index');
	Route::post('/send', 'ContactController@send');
	Route::get('/mentions-legales', 'ContactController@legal');
	Route::get('/sitemap.xml', 'SitemapController@index');
	Route::get('/politique-confidentialite', 'ContactController@confidentialite');
	Route::get('/releases', 'SpotifyController@releases')->name('releases');

	// Social Providers
	Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
	Route::get('/callback/{provider}', 'SocialController@callback');


	// Login, register, etc.
	Auth::routes();

	// Ajax request 
	Route::get('/user', 'UserController@user');

	// Players counter (even no auth users)
	Route::post('/games/{game}/counter', 'GameController@counter');


	// AUTH
	Route::group(['middleware' => ['auth']], function () {

		// Moderators and owner (from api routes)
		Route::get('/media/search', 'Api\DeezerController@search');
		Route::get('/deezer/playlist', 'Api\DeezerController@addPlaylist');
		Route::post('/deezer/store/playlist', 'Api\DeezerController@storePlaylist');

		Route::get('/spotify/search', 'Api\SpotifyController@search');
		Route::get('/spotify/playlist', 'Api\SpotifyController@addPlaylist');
		Route::post('/spotify/store/playlist', 'Api\SpotifyController@storePlaylist');



		Route::get('/faire-un-don', 'DonateController@index')->name('donate');
		Route::post('/donate/success', 'DonateController@success')->name('donate.success');
		Route::post('/donate/error', 'DonateController@error')->name('donate.error');

		// USER PROFILE
		Route::get('/profile', 'ProfileController@index')->name('profile');
		Route::get('/profile/delete', 'ProfileController@destroy')->name('profile.delete');
		Route::get('/profile/stats/games', 'ProfileController@gameStats');
		Route::post('/profile/update', 'ProfileController@update')->name('profile.update');

		// PROFILE PICTURE
		Route::post('/profile/picture', 'ProfileController@uploadPP')->name('profile.picture.upload');
		Route::get('/profile/picture/delete', 'ProfileController@deletePP')->name('profile.picture.delete');

		Route::get('/games/{game}/online', 'GameController@online');
		Route::get('/games/{game}/offline', 'GameController@offline');

		Route::get('/games/me', 'GameController@me')->name('games.me');

		Route::get('/games/{game}/multiplayer', 'GameController@multiplayer')->name('games.multiplayer');
		Route::post('/games/{game}/score', 'GameController@storeScore')->name('games.store.score');
		//Route::get('/home', 'HomeController@index')->name('home');

		Route::post('/games/{game}/create/ticket', 'ModeratorTicketController@store');

		Route::patch('/tracks/{track}', 'TrackController@update');


		Route::get('/stream', 'StreamController@index');
		Route::post('/stream', 'StreamController@track');

		Route::resource('/lab', 'LabController');
		Route::get('/lab/{lab}/vote', 'LabController@vote');

		// CUSTOM GAMES
		Route::get('/games/{game}/tracks/export', 'GameController@export');

		Route::post('/partie/privee/{game}/fetch', 'CustomGameController@fetch')->name('games.custom.fetch');
		Route::get('/partie/privee/{game}/start', 'CustomGameController@start')->name('games.custom.start');
		Route::get('/partie/privee/play/{track}', 'CustomGameController@play')->name('games.custom.play');
		Route::get('/partie/privee/pause/{track}', 'CustomGameController@pause')->name('games.custom.pause');
		Route::get('/partie/privee/resume/{track}', 'CustomGameController@resume')->name('games.custom.resume');
		Route::get('/partie/privee/{game}/stop', 'CustomGameController@stop')->name('games.custom.stop');
		Route::post('/partie/privee/{game}/password', 'CustomGameController@password')->name('games.custom.password');

	});


	Route::get('/games/{game}/private', 'GameController@private')->name('games.private');
	Route::get('/games/{game}/tracks/search', 'TrackController@search');
	Route::post('/games/{game}/random/track', 'GameController@track');
	Route::post('/games/{game}/send/score', 'GameController@sendScore')->name('games.send.score');
	Route::get('/games/{game}/podium', 'GameController@podium');
	Route::get('/games/{game}/tracks/delete', 'GameController@deleteTracks');

	Route::get('/parties/{slug}', 'GameController@slug');
	Route::get('/parties/{slug}/test', 'GameController@test');

	Route::get('/games/privates', 'GameController@privateGames');
	Route::resource('/games', 'GameController');


	// CUSTOM GAMES
	Route::get('/partie/privee/{game}', 'CustomGameController@index')->name('games.custom.index');
	Route::post('/partie/privee/{game}', 'CustomGameController@index')->name('games.custom.password.check');



	// GUEST AUTH FOR BROADCASTING
	Route::post('/broadcasting/auth', function() {

		if (Auth::guest()) {

	        if (Session::get('guest_id')) {

	        	$guest = [
			    	'id' => Session::get('guest_id'),
			    	'name' => Session::get('guest_name')
		    	];

	        } else {

	        	$guest = [
			    	'id' => (int) str_replace('.', '', microtime(true)),
			    	'name' => 'anon_' . random_int(100, 999)
		    	];

	            Session::put('guest_id', $guest['id']);
	            Session::put('guest_name', $guest['name']);
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
	Route::post('/messages/vote', 'Api\MessagesController@vote');
	Route::post('/messages/delete', 'Api\MessagesController@delete'); // Moderator middleware


	Route::get('/user/profil/{user}', 'UserController@show')->name('user.profil');

	Route::resource('/games/{game}/tracks', 'TrackController');

	Route::post('/tracks/{track}/rate/up', 'TrackController@rateUp');
	Route::post('/tracks/{track}/rate/down', 'TrackController@rateDown');


	// MODERATORS
	Route::group(['namespace' => 'Moderator', 'prefix' => 'moderator', 'as' => 'moderator.', 'middleware' => 'moderator'], function () {

		Route::get('/', 'DashboardController@index');

		Route::get('/user/{user}/block', 'UserController@block');
		Route::get('/user/{user}/unblock', 'UserController@unblock');

		Route::post('/users/search', 'UserController@search');
		Route::get('/tickets', 'TicketController@index');
		Route::get('/tickets/{ticket}/close', 'TicketController@close');
		//Chat
		Route::get('/chat', 'ChatController@index');
		Route::post('/chat/send', 'ChatController@store');

	});

	// ADMINS
	Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function() {

		Route::get('/', 'DashboardController@index')->name('dashboard.index');
		Route::post('/', 'DashboardController@index')->name('post.dashboard.index');

		Route::get('users/table', 'UserController@table')->name('users.table');

		Route::get('/users/search', 'UserController@search')->name('users.search');
		Route::post('/users/{user}/role', 'UserController@attach')->name('users.role.attach');
		Route::delete('/users/{user}/game/{game}/role', 'UserController@detach')->name('users.role.detach');
		Route::resource('users', 'UserController');

		Route::get('tracks/duplicates', 'TrackController@duplicates')->name('tracks.duplicates');
		Route::get('tracks/list', 'TrackController@list')->name('tracks.list');
		Route::resource('tracks', 'TrackController');

		Route::resource('games', 'GameController');

	});

//}); // End blockip middleware