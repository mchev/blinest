<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoomController extends AdminController
{
    public function index()
    {
        return Inertia::render('Admin/Rooms/Index', [
            'filters' => Request::all('search', 'trashed'),
            'rooms' => Room::orderBy('updated_at')
                ->filter(Request::only('search', 'trashed'))
                ->get()
                ->transform(fn ($room) => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'owner' => $room->owner,
                    'photo' => $room->photo_path ? URL::route('image', ['path' => $room->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $room->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Rooms/Create');
    }

    public function store()
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('rooms')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::room()->account->rooms()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('rooms') : null,
        ]);

        return Redirect::route('admin.rooms')->with('success', 'Room created.');
    }

    public function edit(Room $room)
    {
        return Inertia::render('Admin/Rooms/Edit', [
            'room' => [
                'id' => $room->id,
                'first_name' => $room->first_name,
                'last_name' => $room->last_name,
                'email' => $room->email,
                'owner' => $room->owner,
                'photo' => $room->photo_path ? URL::route('image', ['path' => $room->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $room->deleted_at,
            ],
        ]);
    }

    public function update(Room $room)
    {
        if (App::environment('demo') && $room->isDemoRoom()) {
            return Redirect::back()->with('error', 'Updating the demo room is not allowed.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('rooms')->ignore($room->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $room->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        if (Request::file('photo')) {
            $room->update(['photo_path' => Request::file('photo')->store('rooms')]);
        }

        if (Request::get('password')) {
            $room->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Room updated.');
    }

    public function destroy(Room $room)
    {
        if (App::environment('demo') && $room->isDemoRoom()) {
            return Redirect::back()->with('error', 'Deleting the demo room is not allowed.');
        }

        $room->delete();

        return Redirect::back()->with('success', 'Room deleted.');
    }

    public function restore(Room $room)
    {
        $room->restore();

        return Redirect::back()->with('success', 'Room restored.');
    }
}
