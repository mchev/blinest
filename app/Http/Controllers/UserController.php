<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return Inertia::render('Me/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo,
                'created_at' => $user->created_at->format('d/m/Y H:i'),
                'created_at_from_now' => $user->created_at->diffForHumans(),
                'latest_round_at' => $user->scores()->latest()->first()?->round?->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'photo' => ['nullable', 'image'],
        ]);

        $user->update(Request::only('name', 'email', 'owner'));

        if (Request::file('photo')) {
            $user->updatePhoto(Request::file('photo'));
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->deletePicture();
        $user->forceDelete();
        Session::flush();
        Auth::logout();

        return redirect('login');
    }

    public function markNotificationAsRead($id)
    {
        Auth::user()->notifications()->where('id', $id)->update([
            'read_at' => now(),
        ]);

        return Redirect::back();
    }
}
