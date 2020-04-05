<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

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