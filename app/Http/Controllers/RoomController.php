<?php

namespace App\Http\Controllers;

use App\Jobs\StartRound;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\User;
use App\Notifications\NewRoomAlert;
use App\Notifications\NewSuggestion;
use App\Rules\Reserved;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Rooms/Index', [
            'filters' => $request->all('search', 'trashed'),
            'rooms' => $request->user()->moderatedRooms()
                ->orderBy('is_public', 'DESC')
                ->orderBy('name')
                ->filter($request->only('search', 'trashed'))
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
                    'is_autostart' => $room->is_autostart,
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

    public function show(Request $request, Room $room)
    {
        if ($room->password && ! $request->has('password')) {
            return Inertia::render('Rooms/Password', [
                'room' => $room,
            ]);
        }

        if ($room->password && $request->input('password') !== $room->password) {
            return redirect()->back()->with('error', __('The password is incorrect'));
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
                'is_random' => $room->is_random,
                'password' => $room->password,
                'latest_messages' => $room->messages()->whereDate('created_at', '>=', now()->subHours(2))->orderByDesc('created_at')->limit(30)->get(),
                'pause_between_tracks' => $room->pause_between_tracks,
                'pause_between_rounds' => $room->pause_between_rounds,
                'tracks_count' => $room->tracks()->count(),
                'is_bookmarked' => $request->user() ? $room->bookmarks()->where('user_id', $request->user()->id)->exists() : false,
            ],
            'public_rooms' => Room::isPublic()->orderBy('name')->select('id', 'slug', 'name', 'photo_path')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Rooms/Create', [
            'categories' => Category::orderBy('name')->select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:25', new Reserved, Rule::unique('rooms')],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $room = $request->user()->rooms()->create([
            'name' => $request->get('name'),
            'category_id' => $request->get('category_id'),
        ]);

        $room->moderators()->attach($request->user());

        return Redirect::route('rooms.edit', $room->id)->with('success', __('Room created'));
    }

    public function edit(Request $request, Room $room)
    {
        $user = $request->user();

        if (! $user->isRoomModerator($room)) {
            return redirect()->route('rooms.index');
        }

        $room->load('moderators', 'playlists');

        $available_playlists = Playlist::isPublic()->get()
            ->merge($user->moderatedPlaylists);

        return Inertia::render('Rooms/Edit', [
            'room' => $room,
            'categories' => Category::orderBy('name')->get(),
            'available_playlists' => $available_playlists,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        if (! $request->user()->isRoomModerator($room)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'max:25', new Reserved, Rule::unique('rooms')->ignore($room->id)],
            'description' => ['nullable'],
            'category_id' => ['required', 'exists:categories,id'],
            'playlist_id' => ['nullable', 'id'],
            'photo' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $room->update($request->only('name', 'description', 'category_id', 'playlist_id'));

        if ($request->file('photo')) {
            $this->authorize('changeRoomPicture');
            $room->updatePhoto($request->file('photo'));
        }

        return Redirect::back()->with('success', __('Room updated'));
    }

    public function updateOptions(Request $request, Room $room)
    {
        $request->validate([
            'password' => ['nullable'],
            'tracks_by_round' => ['required', 'integer', 'min:1', 'max:50'],
            'track_duration' => ['required', 'integer', 'min:5', 'max:30'],
            'pause_between_tracks' => ['required', 'integer', 'min:0', 'max:60'],
            'pause_between_rounds' => ['required', 'integer', 'min:0', 'max:60'],
            'is_chat_active' => ['required', 'boolean'],
            'is_autostart' => ['required', 'boolean'],
            'is_random' => ['required', 'boolean'],
            'color' => ['nullable'],
        ]);

        $room->update($request->only('tracks_by_round', 'track_duration', 'pause_between_tracks', 'pause_between_rounds', 'is_chat_active', 'is_autostart', 'is_random', 'color'));

        // Administrator stuff
        if ($request->user()->isAdministrator()) {
            $room->update([
                'is_featured' => $request->is_featured,
            ]);
        }

        // Room password
        if ($request->get('has_password')) {
            $room->update(['password' => $request->get('password')]);
        } else {
            $room->update(['password' => null]);
        }

        return Redirect::back()->with('success', __('Options updated'));
    }

    public function updateUserCount(Request $request, Room $room)
    {
        $room->update(['user_count' => $request->get('count')]);
    }

    public function destroy(Room $room)
    {
        $room->deletePhoto();
        $room->moderators()->detach();
        $room->delete();

        return Redirect::route('rooms.index')->with('success', __('Room deleted'));
    }

    public function deletePicture(Room $room)
    {
        $room->deletePhoto();

        return Redirect::back()->with('success', __('Room picture deleted'));
    }

    /**
     * Starting a round if no running
     */
    public function joined(Request $request, Room $room)
    {
        if (! $room->is_playing && $room->is_autostart) {
            if (! $room->isPlaying()) { // To be sure there is no round playing
                StartRound::dispatch($room, $request->user());
            }
        }
    }

    public function start(Request $request, Room $room)
    {
        if ($request->user()->hasRoomControl($room) && ! $room->is_playing) {
            DB::transaction(function () use ($room, $request) {
                // Check if there's an active round
                $activeRound = $room->currentRound()->first();

                if (! $activeRound) {
                    // Create a new round
                    $round = $room->rounds()->create([
                        'current' => 1,
                        'is_playing' => true,
                        'user_id' => $request->user()->id,
                    ]);

                    // Update room status
                    $room->update(['is_playing' => true]);

                    // Dispatch the job to start the round
                    StartRound::dispatch($room, $request->user());
                }
            });

            return redirect()->back();
        }

        return abort(403);
    }

    public function alert(Request $request, Room $room)
    {
        $request->validate([
            'message' => ['nullable', 'string', 'max:255'],
        ]);

        $moderators = User::publicModerators()->get();
        foreach ($moderators as $moderator) {
            $moderator->notify(new NewRoomAlert($room, $request->user(), $request->input('message')));
            Cache::forget($moderator->id.'_unread_notifications');
        }

        return redirect()->back();
    }

    public function sendSuggestion(Request $request, Room $room)
    {
        $this->authorize('sendSuggestion');

        $request->validate([
            'suggestion' => ['required'],
        ]);

        foreach ($room->moderators as $moderator) {
            $moderator->notify(new NewSuggestion($room, $request->get('suggestion'), $request->user()));
            Cache::forget($moderator->id.'_unread_notifications');
        }

        return redirect()->back()->with('success', __('Understood!'));
    }

    public function searchTracks(Request $request, Room $room): JsonResponse
    {
        return response()->json(
            $room->tracks()
                ->filter($request->only('search'))
                ->limit(10)
                ->with('answers')
                ->get('id')
        );
    }
}
