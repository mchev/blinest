<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        return Inertia::render('Rooms/Index', [
            'filters' => Request::all('search', 'trashed'),
            'rooms' => Auth::user()->rooms()
                ->orderBy('is_public', 'DESC')
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(5)
                ->withQueryString()
                ->through(fn ($room) => [
                    'id' => $room->id,
                    'photo' => $room->photo,
                    'name' => $room->name,
                    'description' => $room->description,
                    'password' => $room->password,
                    'rounds_count' => $room->rounds()->count(),
                    'moderators' => $room->moderators->map(fn ($moderator) => [
                        'id' => $moderator->id,
                        'name' => $moderator->name,
                    ]),
                    'playlists' => $room->playlists->map(fn ($playlist) => [
                        'id' => $playlist->id,
                        'name' => $playlist->name,
                    ]),
                    'deleted_at' => $room->deleted_at,
                ]),
        ]);
    }

    public function show(Room $room)
    {
        return Inertia::render('Rooms/Show', [
            'room' => [
                'id' => $room->id,
                'name' => $room->name,
                'track_duration' => $room->track_duration,
                'moderators' => $room->moderators,
                'latest_messages' => $room->messages()->latest()->limit(30)->get(),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Rooms/Create', [
            'categories' => Category::orderBy('name')->select('id', 'name')->get(),
        ]);
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:25', Rule::unique('rooms')],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $room = Auth::user()->rooms()->create([
            'name' => Request::get('name'),
            'category_id' => Request::get('category_id'),
        ]);

        return Redirect::route('rooms.edit', $room)->with('success', 'Room created.');
    }

    public function edit(Room $room)
    {
        $room->load('moderators', 'playlists');

        return Inertia::render('Rooms/Edit', [
            'room' => $room,
            'categories' => Category::orderBy('name')->get(),
            'available_playlists' => Playlist::isPublic()->get()->merge(Auth::user()->playlists),
        ]);
    }

    public function update(Room $room)
    {
        Request::validate([
            'name' => ['required', 'max:25', Rule::unique('rooms')->ignore($room->id)],
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

        return Redirect::back()->with('success', 'Room updated.');
    }

    public function destroy(Room $room)
    {
        $room->deletePhoto();
        $room->delete();

        return Redirect::back()->with('success', 'Room deleted.');
    }

    /**
     * Starting a round if no running
     */
    public function joined(Room $room)
    {
        if ($room->isPublic() && ! $room->isPlaying()) {
            $room->startRound();
        }
    }

    public function start(Room $room)
    {
        (Auth::user()->hasRoomControl($room))
            ? $room->startRound()
            : abort(403);
    }

    public function newMessage(Room $room)
    {
        Request::validate([
            'body' => ['required'],
        ]);

        $message = $room->messages()->create([
            'user_id' => Auth::user()->id,
            'user_ip' => Request::ip(),
            'body' => Request::input('body'),
        ]);

        broadcast(new NewMessage($message));
    }
}
