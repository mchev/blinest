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

        return view('users.profile', ["user" => $user]);

    }

}