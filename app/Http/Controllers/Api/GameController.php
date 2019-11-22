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
        $this->middleware('auth', ['except' => ['index']]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::where('public', 1)->orderBy('hit', 'DESC')->get();

        return response()->json($games);
    }


}
