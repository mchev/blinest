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
            'name' => ['required', 'max:50', Rule::unique('rooms')],
            'description' => ['nullable'],
            'playlist_id' => ['nullable', 'id'],
            'password' => ['nullable'],
            'tracks_by_game' => ['required', 'integer'],
            'photo' => ['nullable', 'image'],
            'is_public' => ['required', 'boolean'],
            'is_pro' => ['required', 'boolean'],
            'is_random' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'is_chat_active' => ['required', 'boolean'],
            'discord_webhook_url' => ['nullable', 'url'],
            'color' => ['nullable'],
        ]);

        Auth::user()->rooms()->create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
            'playlist_id' => Request::get('playlist_id'),
            'password' => Request::get('password'),
            'tracks_by_game' => Request::get('tracks_by_game'),
            'is_public' => Request::get('is_public'),
            'is_pro' => Request::get('is_pro'),
            'is_random' => Request::get('is_random'),
            'is_active' => Request::get('is_active'),
            'is_chat_active' => Request::get('is_chat_active'),
            'discord_webhook_url' => Request::get('discord_webhook_url'),
            'color' => Request::get('color'),
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

        $room->update(Request::validate([
            'name' => ['required', 'max:50', Rule::unique('rooms')->ignore($room->id)],
            'description' => ['nullable'],
            'playlist_id' => ['nullable', 'id'],
            'password' => ['nullable'],
            'tracks_by_game' => ['required', 'integer'],
            'photo' => ['nullable', 'image'],
            'is_public' => ['required', 'boolean'],
            'is_pro' => ['required', 'boolean'],
            'is_random' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'is_chat_active' => ['required', 'boolean'],
            'discord_webhook_url' => ['nullable', 'url'],
            'color' => ['nullable'],
        ]));

        //$room->update(Request::only('first_name', 'last_name', 'email', 'owner'));

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

        $room->delete();

        return Redirect::back()->with('success', 'Room deleted.');
    }

    public function restore(Room $room)
    {
        $room->restore();

        return Redirect::back()->with('success', 'Room restored.');
    }
}
