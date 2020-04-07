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
use App\Events\UpdateScore;
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
        $this->middleware('auth', ['except' => ['index', 'show', 'track', 'podium', 'updateScore', 'slug']]);
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

        return response()->json([
            'alltime' => $alltime,
            'month' => $month,
            'week' => $week
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $effects = Effect::all();

        return view('games.create', compact('effects'));
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
            'title' => 'required|max:255|unique:games,title',
            'description' => 'required|max:255',
            'thumbnail' => 'required|file',
        ]);

        $filename = '';

        if ($request->thumbnail) {

            $img = Image::make($request->thumbnail)
                ->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp');

            $filename = uniqid() . '.webp';

            Storage::disk('public')->put('games/'.$filename, $img);

        }

        $slug = Str::slug($request->get('title'), '-');

        $game = new Game([
            'user_id' => Auth::user()->id,
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'thumbnail' => $filename,
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
        return view('games.private', compact('game'));
    }

    /**
     * Display the specified resource by slug.
     *
     * @param  $slug
     * @return \Illuminate\Http\Response
     */
    public function slug($slug)
    {

        $game = Game::where('slug', $slug)->firstOrFail();

        //return view('games.show', compact('game'));
        return view('games.private', compact('game'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function private(Request $request,Game $game)
    {

        return view('games.private', compact('game'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function multiplayer(Game $game)
    {

        return view('games.multiplayer', compact('game'));
    }

    /**
     * Get a random track in the game.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function track(Request $request,Game $game)
    {

        if(!empty($request->answers)) {

            $ids = [];

            foreach ($request->answers as $answer) {
                $ids[] = $answer['id'];
            }

            $track = Track::inRandomOrder()->where('game_id', $game->id)->whereNotIn('id', $ids)->first();

        } else {

            $track = Track::inRandomOrder()->where('game_id', $game->id)->first();

        }

        return response()->json($track);
    }

    /**
     * Save the user score for this game
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function updateScore(Request $request,Game $game)
    {

        $score = ($request->score) ? $request->score : 0;

        if(Auth::user()) {
            $user = Auth::user();
            $user_id = $user->id;
            $user_name = $user->name;
        } else {
            $user_id = null;
            if ($request->session()->get('guest_name')) {
                $user_name = $request->session()->get('guest_name');
            } else {
                $user_name = 'anonyme_' . random_int(100, 999);
                $request->session()->put('guest_name', $user_name);
            }
        }

        $update = [
            'user_id' => $user_id,
            'user_name' => $user_name,
            'game_id' => $game->id,
            'score' => $score
        ];

        broadcast(new UpdateScore($update));

        return response()->json($update);

    }

    /**
     * Save the user score for this game
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function score(Request $request,Game $game)
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
