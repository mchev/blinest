<?php

namespace App\Http\Controllers;

use Auth;
use Image;
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

        return view('user.profile');

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

    public function uploadPP(Request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        if(Auth::user()->hasProfilePicture()) {
            unlink( public_path('/images/players/') . Auth::user()->id . '.webp' );
        }

        $image = $request->file('image');     
        $filePath = public_path('/images/players');

        $img = Image::make($image->path());
        $img->resize(110, 110, function ($const) {
            $const->aspectRatio();
        })
        ->encode('webp')
        ->save($filePath .'/'. Auth::user()->id . '.webp');
   
        return back()->with('success','la photo de profil a été enregistré');

    }

    public function deletePP(Request $request)
    {
        if(Auth::user()->hasProfilePicture()) {
            unlink( public_path('/images/players/') . Auth::user()->id . '.webp' );
        }
        return back()->with('success','la photo de profil a été supprimé');
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
