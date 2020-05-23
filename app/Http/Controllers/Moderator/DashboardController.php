<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;

use App\User;
use App\Game;
use App\Track;
use App\Score;
use Analytics;
use Carbon\Carbon;
use Spatie\Analytics\Period;
use Illuminate\Http\Request;


class DashboardController extends Controller
{


    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('moderator');

    }
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $games = Game::where('public', 1)->orderBy('hit', 'DESC')->get();

        return view('moderators.index', compact('games'));
    }

}
