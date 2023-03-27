<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoomController extends AdminController
{
    public function index()
    {
        return Inertia::render('Admin/Rooms/Index', [
            'filters' => Request::all('search', 'trashed'),
            'rooms' => Room::orderBy('is_public', 'DESC')
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(5)
                ->withQueryString()
                ->through(fn ($room) => [
                    'id' => $room->id,
                    'photo' => $room->photo,
                    'name' => $room->name,
                    'description' => $room->description,
                    'owner' => $room->owner,
                    'moderators' => $room->moderators->map(fn ($moderator) => [
                        'id' => $moderator->id,
                        'name' => $moderator->name,
                    ]),
                    'playlists' => $room->playlists->map(fn ($playlist) => [
                        'id' => $playlist->id,
                        'name' => $playlist->name,
                    ]),
                    'is_public' => $room->is_public,
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
            'name' => ['required', 'max:30', Rule::unique('rooms')],
            'description' => ['nullable'],
            'playlist_id' => ['nullable', 'id'],
            'password' => ['nullable'],
            'tracks_by_round' => ['required', 'integer', 'min:1', 'max:50'],
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

        $room = Auth::user()->rooms()->create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
            'playlist_id' => Request::get('playlist_id'),
            'password' => Request::get('password'),
            'tracks_by_round' => Request::get('tracks_by_round'),
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

        return Redirect::route('admin.rooms.edit', $room)->with('success', __('Room created'));
    }

    public function edit(Room $room)
    {
        return Inertia::render('Admin/Rooms/Edit', [
            'room' => $room->makeVisible(['discord_webhook_url']),
            'categories' => Category::orderBy('name')->get(),
            'moderators' => $room->moderators,
            'playlists' => $room->playlists,
            'available_playlists' => Playlist::isPublic()->get()->merge(Auth::user()->playlists),
        ]);
    }

    public function update(Room $room)
    {
        Request::validate([
            'name' => ['required', 'max:30', Rule::unique('rooms')->ignore($room->id)],
            'description' => ['nullable'],
            'category_id' => ['required', 'exists:categories,id'],
            'playlist_id' => ['nullable', 'id'],
            'password' => ['nullable'],
            'tracks_by_round' => ['required', 'integer', 'min:1', 'max:50'],
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

        $room->update(Request::only('name', 'description', 'category_id', 'playlist_id', 'tracks_by_round', 'track_duration', 'pause_between_tracks', 'pause_between_rounds', 'is_public', 'is_pro', 'is_random', 'is_active', 'is_chat_active', 'discord_webhook_url', 'color'));

        if (Request::file('photo')) {
            $room->updatePhoto(Request::file('photo'));
        }

        if (Request::get('password')) {
            $room->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', __('Room updated'));
    }

    public function destroy(Room $room)
    {
        $room->deletePhoto();
        $room->delete();

        return Redirect::back()->with('success', __('Room deleted'));
    }

    public function restore(Room $room)
    {
        $room->restore();

        return Redirect::back()->with('success', __('Room restored'));
    }
}
