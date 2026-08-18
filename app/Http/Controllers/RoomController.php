<?php

namespace App\Http\Controllers;

use App\Events\RoomPublicState;
use App\Events\RoomState as RoomStateEvent;
use App\Jobs\StartRound;
use App\Jobs\UpdateUserLevel;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Track;
use App\Models\User;
use App\Notifications\NewRoomAlert;
use App\Notifications\NewSuggestion;
use App\Rules\Reserved;
use App\Seo\RoomHead;
use App\Seo\SeoLandingHtml;
use App\Services\Auth\GuestAuthService;
use App\Services\RoomPresenceService;
use App\Services\Rooms\OfficialRoomRegistry;
use App\Services\Rooms\RoomContentService;
use App\Services\Rooms\RoomLandingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class RoomController extends Controller
{
    public function __construct(
        private RoomHead $roomHead,
        private OfficialRoomRegistry $officialRooms,
        private RoomContentService $roomContent,
        private RoomLandingService $roomLanding,
        private GuestAuthService $guestAuth,
        private SeoLandingHtml $seoLandingHtml,
    ) {}

    public function index(Request $request)
    {
        Head::title(__('My Rooms'));

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
                    'rounds_count' => Cache::flexible("room_{$room->id}_rounds_count", [300, 900], fn () => $room->rounds()->count()),
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
        // Return 404 if room is deleted
        if ($room->trashed()) {
            abort(404);
        }

        if ($room->password && ! $request->has('password')) {
            $room->load('category', 'owner');

            // Check if rounds_count is in cache before calling flexible (to display stat)
            $roundsCountFromCache = Cache::get("room_{$room->id}_rounds_count");
            $roundsCount = Cache::flexible("room_{$room->id}_rounds_count", [300, 900], fn () => $room->rounds()->count());

            $this->roomHead->apply($room, $roundsCount, isPasswordProtected: true);

            return Inertia::render('Rooms/Password', [
                'room' => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'description' => $room->description,
                    'photo' => $room->photo,
                    'slug' => $room->slug,
                    'rounds_count' => $roundsCount,
                    'rounds_count_from_cache' => $roundsCountFromCache !== null,
                ],
            ]);
        }

        if ($room->password && $request->input('password') !== $room->password) {
            return redirect()->back()->with('error', __('The password is incorrect'));
        }

        $this->guestAuth->ensureGuestSession();

        $room->load('category', 'owner');

        // Check if rounds_count is in cache before calling flexible (to display stat)
        $roundsCountFromCache = Cache::get("room_{$room->id}_rounds_count");
        $roundsCount = Cache::flexible("room_{$room->id}_rounds_count", [300, 900], fn () => $room->rounds()->count());

        // Check if tracks_count is in cache before calling flexible (to display stat)
        $tracksCountFromCache = Cache::get("room_{$room->id}_tracks_count");
        $tracksCount = Cache::flexible("room_{$room->id}_tracks_count", [300, 900], fn () => $room->tracks()->count());

        $stats = [
            'rounds' => $roundsCount,
            'tracks' => $tracksCount,
            'players_online' => $this->roomLanding->playersOnline($room),
        ];

        $isOfficialSeoRoom = $this->officialRooms->isOfficialSeoRoom($room);
        $seo = null;

        if ($isOfficialSeoRoom) {
            $seo = [
                'content' => $this->roomContent->forRoom($room, $stats),
                'breadcrumbs' => $this->roomLanding->breadcrumbs($room),
                'similar_rooms' => $this->roomLanding->similarRooms($room),
                'stats' => $stats,
            ];
        }

        $this->roomHead->apply(
            $room,
            $roundsCount,
            roomContent: $isOfficialSeoRoom ? $this->roomContent : null,
            stats: $stats,
            landingContent: $seo['content'] ?? null,
            breadcrumbs: $seo['breadcrumbs'] ?? null,
        );

        if ($seo !== null) {
            $this->seoLandingHtml->shareRoom([
                'room' => $room,
                'seo' => $seo,
            ]);
        }

        return Inertia::render('Rooms/Show', [
            'seo' => $seo,
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
                'tracks_count' => $tracksCount,
                'tracks_count_from_cache' => $tracksCountFromCache !== null,
                'is_bookmarked' => $request->user() ? $room->bookmarks()->where('user_id', $request->user()->id)->exists() : false,
                'category' => $room->category,
                'owner' => $room->owner,
                'rounds_count' => $roundsCount,
                'rounds_count_from_cache' => $roundsCountFromCache !== null,
            ],
            'public_rooms' => Room::isPublic()
                ->whereNull('password')
                ->orderBy('name')
                ->get()
                ->map(fn (Room $publicRoom) => $publicRoom->toHomepageArray())
                ->values()
                ->all(),
        ]);
    }

    public function create()
    {
        Head::title('Create Room');

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

        // Update user level in queue when creating a room
        UpdateUserLevel::dispatch(
            user: $request->user(),
            type: 'rooms_count'
        );

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

        Head::title(__('Edit Room'));

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

        $oldPlaylistId = $room->playlist_id;
        $room->update($request->only('name', 'description', 'category_id', 'playlist_id'));

        // Invalidate tracks_count cache if playlist changed
        if ($oldPlaylistId != $room->playlist_id) {
            Cache::forget("room_{$room->id}_tracks_count");
        }

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
    public function joined(Request $request, Room $room): JsonResponse
    {
        if (! $room->is_playing && $room->is_autostart) {
            if (! $room->isPlaying()) { // To be sure there is no round playing
                StartRound::dispatch($room, $request->user());
            }
        }

        $roomPresence = app(RoomPresenceService::class);
        $roomState = $roomPresence->getRoomState($room);

        // Return current round and track information if a round is playing
        $currentRound = $room->currentRound()->first();

        // Refresh the round to ensure we have the latest current_track_started_at
        if ($currentRound) {
            $currentRound->refresh();
        }

        $roundTracksOrdered = $currentRound && $currentRound->is_playing
            ? array_values((array) $currentRound->tracks)
            : [];

        $playedTracksPayload = [];
        if ($currentRound && $currentRound->is_playing) {
            $currentCounter = (int) ($currentRound->current ?? 0);
            if ($currentCounter >= 2) {
                $finishedIds = [];
                for ($i = 0; $i <= $currentCounter - 2; $i++) {
                    if (isset($roundTracksOrdered[$i])) {
                        $finishedIds[] = $roundTracksOrdered[$i];
                    }
                }
                if ($finishedIds !== []) {
                    $finishedTracks = Track::query()
                        ->with(['answers.type'])
                        ->whereIn('id', $finishedIds)
                        ->get()
                        ->keyBy('id');
                    foreach ($finishedIds as $finishedId) {
                        $finishedTrack = $finishedTracks->get($finishedId);
                        if ($finishedTrack) {
                            $playedTracksPayload[] = $this->playlistTrackPayloadForRoom($finishedTrack);
                        }
                    }
                }
            }
        }

        if ($currentRound && $currentRound->is_playing) {
            // Get the current track being played
            // Convert tracks to array since it's cast as object
            $tracks = $roundTracksOrdered;
            $currentTrackIndex = $currentRound->current ?? 0;
            if ($currentTrackIndex > 0 && isset($tracks[$currentTrackIndex - 1])) {
                $trackId = $tracks[$currentTrackIndex - 1];
                $track = Track::with('answers')->find($trackId);

                if ($track) {
                    // Calculate elapsed time since track started
                    $startTime = 0;
                    if ($currentRound->current_track_started_at) {
                        $startedAt = $currentRound->current_track_started_at;
                        $elapsedSeconds = $startedAt->diffInSeconds(now());
                        $startTime = max(0, min($elapsedSeconds, $room->track_duration));
                    }

                    return response()->json([
                        'round' => [
                            'id' => $currentRound->id,
                            'current' => $currentRound->current,
                            'is_playing' => $currentRound->is_playing,
                            'current_track_started_at' => $currentRound->current_track_started_at?->toIso8601String(),
                            'tracks' => $roundTracksOrdered,
                        ],
                        'track' => [
                            'id' => $track->id,
                            'provider' => $track->provider,
                            'preview_url' => $track->preview_url,
                            'audio' => $track->audio,
                            'answers' => $track->answers->map(function ($answer) {
                                return [
                                    'id' => $answer->id,
                                    'name' => $answer->type->name,
                                ];
                            }),
                        ],
                        'room' => [
                            'id' => $room->id,
                            'is_playing' => $room->is_playing,
                        ],
                        'startTime' => $startTime,
                        'scores' => $roomState['scores'],
                        'users' => $roomState['users'],
                        'roundId' => $roomState['roundId'],
                        'playedTracks' => $playedTracksPayload,
                    ]);
                }
            }
        }

        return response()->json([
            'round' => null,
            'track' => null,
            'startTime' => 0,
            'scores' => $roomState['scores'],
            'users' => $roomState['users'],
            'roundId' => $roomState['roundId'],
            'playedTracks' => $playedTracksPayload,
        ]);
    }

    /**
     * Notify server that the user joined the room presence (Echo.join).
     * Server adds user to Redis and broadcasts RoomState to all clients.
     * Returns the new state so the caller can update immediately (avoids race when alone).
     */
    public function presenceJoined(Request $request, Room $room): JsonResponse
    {
        $roomPresence = app(RoomPresenceService::class);
        $roomPresence->addMember($room, $request->user());
        $state = $roomPresence->getRoomState($room);
        broadcast(new RoomStateEvent($room->id, $state));
        broadcast(new RoomPublicState($room));

        return response()->json([
            'ok' => true,
            'users' => $state['users'],
            'scores' => $state['scores'],
            'roundId' => $state['roundId'],
        ]);
    }

    /**
     * Notify server that the user left the room presence (Echo.leave / page unload).
     * Server removes user from Redis and broadcasts RoomState to all clients.
     */
    public function presenceLeft(Request $request, Room $room): JsonResponse
    {
        $roomPresence = app(RoomPresenceService::class);
        $roomPresence->removeMember($room, $request->user());
        $state = $roomPresence->getRoomState($room);
        broadcast(new RoomStateEvent($room->id, $state));
        broadcast(new RoomPublicState($room));

        return response()->json(['ok' => true]);
    }

    /**
     * Public snapshot for homepage room cards (no auth).
     */
    public function publicState(Room $room): JsonResponse
    {
        if ($room->trashed()) {
            abort(404);
        }

        return response()->json($room->publicStatePayload());
    }

    public function start(Request $request, Room $room)
    {
        if ($request->user()->hasRoomControl($room) && ! $room->is_playing) {
            DB::transaction(function () use ($room, $request) {
                // Check if there's an active round
                $activeRound = $room->currentRound()->first();

                if ($activeRound) {
                    $activeRound->stop();
                }

                // Create a new round (current will be set by StartRound when tracks are added)
                $room->rounds()->create([
                    'current' => 0,
                    'is_playing' => true,
                    'user_id' => $request->user()->id,
                ]);

                // Invalidate rounds count cache
                Cache::forget("room_{$room->id}_rounds_count");

                // Update room status
                $room->update(['is_playing' => true]);

                // Dispatch the job to start the round
                StartRound::dispatch($room, $request->user());

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

    /**
     * Payload for room playlist (Answers card), aligned with TrackEnded / AnswerCard shape.
     * Avoids resolving the `audio` accessor N times on join (no live Deezer fetch per track).
     *
     * @return array<string, mixed>
     */
    private function playlistTrackPayloadForRoom(Track $track): array
    {
        $track->loadMissing('answers.type');

        return [
            'id' => $track->id,
            'provider' => $track->provider,
            'preview_url' => $track->preview_url,
            'artwork_url' => $track->artwork_url,
            'album_name' => null,
            'hint' => $track->hint,
            'upvotes' => $track->upvotes,
            'downvotes' => $track->downvotes,
            'answers' => $track->answers->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'value' => $answer->value,
                    'type' => [
                        'name' => $answer->type->name,
                    ],
                ];
            })->all(),
        ];
    }
}
