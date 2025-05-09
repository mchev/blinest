<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\TotalScore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModeratorController extends Controller
{
    public function index(Request $request)
    {
        $roomsWithModerators = Room::isPublic()
            ->select('id', 'name', 'created_at')
            ->withCount(['moderators'])
            ->with(['moderators' => function ($query) use ($request) {
                if ($request->search) {
                    $query->where(function ($q) use ($request) {
                        $searchTerm = "%{$request->search}%";
                        $q->where('name', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                    });
                }

                $query->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.updated_at',
                    'users.photo_path',
                ])
                    ->withCount(['moderatedRooms', 'moderatedPlaylists'])
                    ->with(['messages' => function ($q) {
                        $q->latest()->take(1);
                    }]);
            }])
            ->orderBy('name')
            ->limit(50)
            ->get();

        $moderatorIds = $roomsWithModerators->pluck('moderators.*.id')->flatten()->unique();

        $latestScores = TotalScore::select('totalscorable_id', 'updated_at')
            ->whereIn('totalscorable_id', $moderatorIds)
            ->where('totalscorable_type', User::class)
            ->groupBy('totalscorable_id')
            ->selectRaw('MAX(updated_at) as latest_score_date')
            ->get()
            ->keyBy('totalscorable_id');

        $sixMonthsAgo = now()->subMonths(8);

        return Inertia::render('Moderation/Moderators', [
            'roomsWithModerators' => $roomsWithModerators->map(function ($room) use ($latestScores, $sixMonthsAgo) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'created_at' => $room->created_at->format('d/m/Y'),
                    'moderators_count' => $room->moderators_count,
                    'scores_count' => $room->scores_count,
                    'moderators' => $room->moderators->map(function ($moderator) use ($latestScores, $sixMonthsAgo) {
                        $lastScoreDate = $latestScores->get($moderator->id)?->latest_score_date;
                        $lastMessageDate = $moderator->messages->first()?->created_at;

                        $lastActivity = max(
                            $lastScoreDate ? Carbon::parse($lastScoreDate) : Carbon::create(0),
                            $lastMessageDate ?? Carbon::create(0)
                        );

                        return [
                            'id' => $moderator->id,
                            'name' => $moderator->name,
                            'photo' => $moderator->profile_photo_url,
                            'last_connection' => $moderator->updated_at 
                                ? 'Connexion ' . $moderator->updated_at->diffForHumans()
                                : 'Jamais connecté',
                            'last_game_activity' => $lastScoreDate
                                ? 'Score ' . Carbon::parse($lastScoreDate)->diffForHumans()
                                : 'Aucun score enregistré',
                            'last_message_date' => $lastMessageDate
                                ? 'Message ' . $lastMessageDate->diffForHumans()
                                : 'Aucun message enregistré',
                            'moderated_rooms_count' => $moderator->moderated_rooms_count,
                            'moderated_playlists_count' => $moderator->moderatedPlaylists->count(),
                            'is_inactive' => $lastActivity->isBefore($sixMonthsAgo),
                        ];
                    }),
                ];
            }),
            'filters' => $request->only(['search']),
        ]);
    }
}
