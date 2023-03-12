<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use App\Notifications\NewRoomAlert;
use App\Notifications\NewSuggestion;
use App\Rules\Reserved;
use Illuminate\Http\JsonResponse;
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
                ->with('moderators', 'playlists', 'category')
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
                    'category' => $room->category,
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
        if ($room->password) {
            if (Request::has('password')) {
                if (Request::input('password') != $room->password) {
                    return redirect()->back()->with('error', __('The password is incorrect.')); 
                }
            } else {
                return Inertia::render('Rooms/Password', [
                    'room' => $room,
                ]);
            }
        }

        return Inertia::render('Rooms/Show', [
            'room' => [
                'id' => $room->id,
                'name' => $room->name,
                'description' => $room->description,
                'photo' => $room->photo,
                'slug' => $room->slug,
                'url' => url('/rooms/'.$room->slug),
                'is_playing' => $room->is_playing,
                'track_duration' => $room->track_duration,
                'tracks_by_round' => $room->tracks_by_round,
                'moderators' => $room->moderators,
                'is_chat_active' => $room->is_chat_active,
                'is_autostart' => $room->is_autostart,
                'password' => $room->password,
                'latest_messages' => $room->messages()->whereDate('created_at', '>=', now()->subHours(2))->orderByDesc('created_at')->limit(30)->get(),
                'pause_between_tracks' => $room->pause_between_tracks,
                'pause_between_rounds' => $room->pause_between_rounds,
                'tracks_count' => $room->tracks()->count(),
                'is_bookmarked' => $room->bookmarks()->where('user_id', auth()?->user()?->id)->exists(),
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
            'name' => ['required', 'max:25', new Reserved, Rule::unique('rooms')],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $room = Auth::user()->rooms()->create([
            'name' => Request::get('name'),
            'category_id' => Request::get('category_id'),
        ]);

        return Redirect::route('rooms.edit', $room->id)->with('success', 'Room created.');
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
            'name' => ['required', 'max:25', new Reserved, Rule::unique('rooms')->ignore($room->id)],
            'description' => ['nullable'],
            'category_id' => ['required', 'exists:categories,id'],
            'playlist_id' => ['nullable', 'id'],
            'photo' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $room->update(Request::only('name', 'description', 'category_id', 'playlist_id'));

        if (Request::file('photo')) {
            if ($room->is_public || $room->is_pro || Auth()->user()->canUpdateRoomPicture()) {
                $room->updatePhoto(Request::file('photo'));
            }
        }

        return Redirect::back()->with('success', 'Room updated.');
    }

    public function updateOptions(Room $room)
    {
        Request::validate([
            'password' => ['nullable'],
            'tracks_by_round' => ['required', 'integer', 'min:1', 'max:100'],
            'track_duration' => ['required', 'integer', 'min:5', 'max:30'],
            'pause_between_tracks' => ['required', 'integer', 'min:0', 'max:60'],
            'pause_between_rounds' => ['required', 'integer', 'min:0', 'max:60'],
            'is_chat_active' => ['required', 'boolean'],
            'is_autostart' => ['required', 'boolean'],
            'color' => ['nullable'],
        ]);

        $room->update(Request::only('tracks_by_round', 'track_duration', 'pause_between_tracks', 'pause_between_rounds', 'is_chat_active', 'is_autostart', 'color'));

        if (Request::get('has_password')) {
            $room->update(['password' => Request::get('password')]);
        } else {
            $room->update(['password' => null]);
        }

        return Redirect::back()->with('success', 'Options updated.');
    }

    public function destroy(Room $room)
    {
        $room->deletePhoto();
        $room->moderators()->detach();
        $room->delete();

        return Redirect::route('rooms.index')->with('success', 'Room deleted.');
    }

    /**
     * Starting a round if no running
     */
    public function joined(Room $room)
    {
        if (Auth::user()) {
            if (! $room->is_playing && $room->is_autostart) {
                $room->startRound();
            }

            return response()->json('Successfully joined the room.');
        } else {
            return response()->json('User is not logged in.');
        }
    }

    public function start(Room $room)
    {
        if (Auth::user()->hasRoomControl($room) && ! $room->is_playing) {
            $room->startRound();

            return redirect()->back();
        }

        return abort(403);
    }

    public function alert(Room $room)
    {
        Request::validate([
            'message' => ['nullable', 'string', 'max:255'],
        ]);

        $moderators = User::publicModerators()->get();
        foreach ($moderators as $moderator) {
            $moderator->notify(new NewRoomAlert($room, Auth::user(), Request::input('message')));
        }

        return redirect()->back();
    }

    public function sendSuggestion(Room $room)
    {
        Request::validate([
            'suggestion' => ['required'],
        ]);

        foreach ($room->playlists as $playlist) {
            foreach ($playlist->moderators as $moderator) {
                $moderator->notify(new NewSuggestion($room, Request::get('suggestion'), Auth::user()));
            }
        }

        return redirect()->back()->with('success', 'Bien reçu!');
    }

    public function searchTracks(Room $room): JsonResponse
    {
        return response()->json(
            $room->tracks()
                ->filter(Request::only('search'))
                ->limit(10)
                ->with('answers')
                ->get('id')
        );
    }
}
