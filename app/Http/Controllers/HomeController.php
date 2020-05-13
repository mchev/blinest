<?php

namespace App\Http\Controllers;

use App\Track;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::guest()) {

            if (!Session::get('guest_id')) {

                $guest = [
                    'id' => (int) str_replace('.', '', microtime(true)),
                    'name' => 'anon_' . random_int(100, 999)
                ];

                Session::put('guest_id', $guest['id']);
                Session::put('guest_name', $guest['name']);
            }

        }

        $counter = Track::count();

        return view('welcome', compact('counter'));
    }
}
