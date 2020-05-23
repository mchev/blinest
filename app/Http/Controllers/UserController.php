<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function user(Request $request) {

        if(!$request->ajax()){

            abort(404);

        } else {

            if (auth()->user()) {

                $user = [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name
                ];

            } else {

                $user = [
                    'id' => $request->session()->get('guest_id'),
                    'name' => $request->session()->get('guest_name')
                ];

            }

            return response()->json($user);

        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $user = User::where('name',$name)->first();
        $scores = $user->scores()->paginate(10);

        return view('users.profile', ["user" => $user, "scores" => $scores]);

    }


}