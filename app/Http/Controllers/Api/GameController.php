<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;


use App\Game;
use Illuminate\Http\Request;

class GameController extends BaseController
{


    /**
     * Instantiate a new GameController instance.
     */
    public function __construct()
    {
        //$this->middleware('auth', ['except' => ['index']]);

    }


    /**
     * Display the list of public games
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::where('public', 1)->orderBy('hit', 'DESC')->get();

        return response()->json($games);
    }


    /**
     * Search games by name or username.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $games = Game::where('title', 'like', '%' . $request->get('query') . '%')
                ->orWhereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->get('query') . '%');
                })
                //->has('tracks', '>', 50)
                ->orderBy('hit', 'DESC')
                ->withCount('tracks')
                ->with('user')
                ->paginate(5);

        return response()->json($games);
    }


}
