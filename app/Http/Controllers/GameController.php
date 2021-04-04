<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Spatie\Sitemap\SitemapGenerator;
use App\Game;
use App\Round;
use App\Effect;
use App\Track;
use App\Score;
use App\Events\ScoreSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameController extends Controller
{

    /**
     * Instantiate a new GameController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'track', 'counter', 'podium', 'sendScore', 'slug', 'custom', 'privateGames']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counter = Track::count();
        $games = Game::where('public', 1)->orderBy('hit', 'DESC')->get();

        return view('welcome', compact('games', 'counter'));
    }

    public function privateGames()
    {
        $games = Game::where('public', 0)
                    ->where('online', 1)
                    ->where(function($query) { 
                        $query->where('password', null)->orWhere('password', '');
                    })
                    ->has('tracks', '>' , 20)
                    ->orderBy('hit', 'DESC')
                    ->limit(10)
                    ->get();

        $online = Game::where('online', 1)->where('updated_at', '<=', now()->subSeconds(30))->get();
        foreach ($online as $item) {
            $item->online = null;
            $item->update();
        }


        return response()->json($games);
    }

    public function online(Game $game)
    {
        if (auth()->user()->id == $game->user_id) {
            
            $game->online = 1;
            $game->update();
        
        }
    }

    public function offline(Game $game)
    {
        if (auth()->user()->id == $game->user_id) {
            $game->online = null;
            $game->update();
        }
    }

    public function counter(Request $request, Game $game) {

        $request->validate([
            'counter' => 'required|integer',
        ]);

        $game->counter = $request->counter;
        $game->update();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $games = Game::where('user_id', Auth::user()->id)->orderBy('hit', 'DESC')->get();

        return view('games.me', compact('games'));
    }

    /**
     * Return the top 10 game
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function podium(Request $request,Game $game)
    {

        $alltime = $game->podium();
        $month = $game->podiumMonth();
        $week = $game->podiumWeek();
        $userScore = null;

        if(Auth::user()) {
            $userScore = Auth::user()->scores->where('game_id', $game->id)->sum('score');
        }
        

        return response()->json([
            'alltime' => $alltime,
            'month' => $month,
            'week' => $week,
            'user_score' => $userScore,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|max:30|unique:games,title',
            'description' => 'required|max:255',
        ]);

        $slug = Str::slug($request->get('title'), '-');

        $game = new Game([
            'user_id' => Auth::user()->id,
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'public' => 0,
            'random' => 1,
            'tracks_number' => 15,
            'slug' => $slug
        ]);

        $game->save();

        return view('games.edit', compact('game'))->with('success', 'Le blind test a été ajouté');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Game $game)
    {

        //return view('games.show', compact('game'));
        return view('games.public', compact('game'));
    }

    /**
     * Display the specified resource by slug.
     *
     * @param  $slug
     * @return \Illuminate\Http\Response
     */
    public function slug(Request $request, $slug)
    {

        $game = Game::where('slug', $slug)->firstOrFail();

        if (Auth::guest()) {

            if (!$request->session()->get('guest_id')) {

                $guest = [
                    'id' => (int) str_replace('.', '', microtime(true)),
                    'name' => 'anon_' . random_int(100, 999)
                ];

                $request->session()->put('guest_id', $guest['id']);
                $request->session()->put('guest_name', $guest['name']);
            }

        }

        if ($game->public) {

            return view('games.public.index', compact('game'));

        } else {

            if ( $game->password !== '' ) {

                if( $request->get('password') == $game->password ) {

                    return view('games.custom.index', compact('game'));

                } else {

                    return view('games.custom.password', compact('game'));

                }

            } else {

                return view('games.custom.index', compact('game'));

            }

        }

    }



    /**
     * Save the user score for this game
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function sendScore(Request $request, Game $game)
    {

        $user = $request->get('user');

        broadcast(new ScoreSent( $user, $game ));

    }

    /**
     * Save the user score for this game
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function storeScore(Request $request,Game $game)
    {

        $game->hit = $game->hit + 1;
        $game->save();

        if(Auth::user()) {

            $score = new Score([
                'game_id' => $game->id,
                'user_id' => Auth::user()->id,
                'score' => $request->score
            ]);

            $score->save();

            return response()->json($score);

        } else {

            return response()->json("Utilisateur non connecté. Pas d'enregistrement du score.");

        }

    }



    public function deleteTracks(Request $request,Game $game)
    {

        Track::where('game_id', $game->id)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {

        $request->validate([
            'title' => 'required|max:255|unique:games,title,' . $game->id,
            'description' => 'required|max:255',
        ]);

        $filename = '';

        if ($request->thumbnail) {

            $img = Image::make($request->thumbnail)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp');

            $filename = uniqid() . '.webp';

            Storage::disk('public')->put('games/'.$filename, $img);

            $game->thumbnail = $filename;

        }

        // Generate new slug
        $slug = Str::slug($request->get('title'), '-');

        $game->title = $request->get('title');
        $game->description = $request->get('description');
        $game->slug = $slug;

        $game->save();

        return view('games.edit', compact('game'))->with('success', 'Le blind test a été modifié');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return redirect('/')->with('success', 'Le blind test a été supprimé');
    }
}
