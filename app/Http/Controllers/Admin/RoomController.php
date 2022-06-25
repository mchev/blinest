<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Category;
use App\Models\Playlist;
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
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($room) => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'description' => $room->description,
                    'owner' => $room->owner,
                    'photo' => $room->photo,
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
            'tracks_by_game' => ['required', 'integer', 'min:1', 'max:50'],
            'track_duration' => ['required', 'integer', 'min:5', 'max:30'],
            'pause_between_tracks' => ['required', 'integer', 'min:0', 'max:60'],
            'pause_between_rounds' => ['required', 'integer', 'min:0', 'max:60'],
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
            'track_duration' => Request::get('track_duration'),
            'pause_between_tracks' => Request::get('pause_between_tracks'),
            'pause_between_rounds' => Request::get('pause_between_rounds'),
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
            'room' => $room,
            'room_playlists' => $room->playlists()->pluck('id'),
            'categories' => Category::orderBy('name')->get(),
            'available_playlists' => Playlist::isPublic()->get()->merge(Auth::user()->playlists),
        ]);
    }

    public function update(Room $room)
    {

        Request::validate([
            'name' => ['required', 'max:50', Rule::unique('rooms')->ignore($room->id)],
            'description' => ['nullable'],
            'category_id' => ['required', 'exists:categories,id'],
            'playlist_id' => ['nullable', 'id'],
            'password' => ['nullable'],
            'tracks_by_game' => ['required', 'integer', 'min:1', 'max:50'],
            'track_duration' => ['required', 'integer', 'min:5', 'max:30'],
            'pause_between_tracks' => ['required', 'integer', 'min:0', 'max:60'],
            'pause_between_rounds' => ['required', 'integer', 'min:0', 'max:60'],
            'photo' => ['nullable', 'image'],
            'is_public' => ['required', 'boolean'],
            'is_pro' => ['required', 'boolean'],
            'is_random' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'is_chat_active' => ['required', 'boolean'],
            'discord_webhook_url' => ['nullable', 'url'],
            'color' => ['nullable'],
        ]);

        $room->playlists()->sync(Request::input('playlists'));

        $room->update(Request::only('name', 'description', 'category_id', 'playlist_id', 'tracks_by_game', 'track_duration', 'pause_between_tracks', 'pause_between_rounds', 'is_public', 'is_pro', 'is_random', 'is_active', 'is_chat_active', 'discord_webhook_url', 'color'));

        if (Request::file('photo')) {
            $room->updatePhoto(Request::file('photo'));
        }

        if (Request::get('password')) {
            $room->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'Room updated.');
    }

    public function destroy(Room $room)
    {

        $room->deletePhoto();
        $room->delete();

        return Redirect::back()->with('success', 'Room deleted.');
    }

    public function restore(Room $room)
    {
        $room->restore();

        return Redirect::back()->with('success', 'Room restored.');
    }

    public function attachPlaylist(Room $room, Playlist $playlist)
    {
        $room->playlists()->attach($playlist);
        return Redirect::back();
    }

    public function detachPlaylist(Room $room, Playlist $playlist)
    {
        $room->playlists()->detach($playlist);
        return Redirect::back();
    }

}
