<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends AdminController
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => User::orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'is_admin' => $user->isAdmin(),
                    'email' => $user->email,
                    'team' => $user->team,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email:rfc,dns', Rule::unique('users')],
            'password' => ['nullable'],
            'is_admin' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        User::create([
            'name' => Request::get('name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'is_admin' => Request::get('is_admin'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        return Redirect::route('admin.users')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'team_id' => ($user->team) ? $user->team->id : null,
                'is_admin' => $user->isAdmin(),
                'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
        }

        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
            'team_id' => ['nullable', 'integer'],
            'is_admin' => ['boolean'],
            'password' => ['nullable'],
            'photo' => ['nullable', 'image'],
        ]);

        $user->update(Request::only('name', 'email', 'team_id', 'is_admin'));

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}
