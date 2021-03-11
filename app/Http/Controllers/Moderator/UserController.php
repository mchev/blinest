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

    /**
     * Search games by name or username.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $users = User::where('name', 'like', '%' . $request->search . '%')
                ->with('messages')
                ->with('latestScore')
                ->paginate(10);

        return response()->json($users);
    }

    public function block(User $user)
    {

        if (auth()->user()->is('moderator')) {
            $user->banned_until = now()->addDay();
            $user->update();

            return response()->json($user);
        }
    }

    public function unblock(User $user)
    {

        if (auth()->user()->is('moderator')) {
            $user->banned_until = null;
            $user->update();

            return response()->json($user);
        }
    }

}