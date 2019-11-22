<?php

namespace App\Http\Controllers;

use Auth;
use App\Score;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $gamesCounter = Score::where('user_id', Auth::user()->id)->count();

        $stats = Score::where('user_id', Auth::user()->id)
                    ->selectRaw('game_id, COUNT(*) as total, MAX(score) as score')
                    ->groupBy('game_id')
                    ->orderBy('score', 'DESC')
                    ->with('game')
                    ->get();

        $bestScore = Score::selectRaw('game_id, COUNT(*) as total, MAX(score) as score')
                    ->groupBy('game_id')
                    ->get();

        return view('user.profile', compact('gamesCounter', 'stats', 'bestScore'));

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => 'required|max:255|unique:users,name,'.$user->id,
            'email' => 'required|max:255|unique:users,email,'.$user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect('/profile#about')->with('success', 'Votre compte a été modifié');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        Auth::user()->delete();
        return redirect('/')->with('success', 'Votre compte a été supprimé');

    }
}
