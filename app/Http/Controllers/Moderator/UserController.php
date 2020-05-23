<?php

namespace App\Http\Controllers\Moderator;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
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


    public function block(User $user)
    {

        if (auth()->user()->is('moderator')) {
            $user->banned_until = now()->addHour();
            $user->update();

            return response()->json('Utilisateur banni pour 1 heure.');
        }
    }

}