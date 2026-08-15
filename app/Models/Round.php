<?php

namespace App\Models;

use App\Events\RoomPublicState;
use App\Events\RoundFinished;
use App\Events\TrackPaused;
use App\Events\TrackPlayed;
use App\Events\TrackResumed;
use App\Jobs\ProcessDeletedTrack;
use App\Jobs\ProcessRoundFinalization;
use App\Jobs\ProcessRoundFinished;
use App\Jobs\ProcessTrackPlayed;
use App\Services\RoundScoreService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'current',
        'finished_at',
        'is_playing',
        'current_track_started_at',
    ];

    protected function casts(): array
    {
        return [
            'tracks' => 'object',
            'finished_at' => 'datetime',
            'current_track_started_at' => 'datetime',
        ];
    }

    public function pause()
    {
        $this->update(['is_playing' => false]);
        broadcast(new TrackPaused($this->room));
    }

    public function resume()
    {
        $this->update(['is_playing' => true]);
        broadcast(new TrackResumed($this->room));
    }

    public function stop()
    {
        DB::transaction(function () {
            $this->update([
                'is_playing' => false,
                'finished_at' => now(),
            ]);

            if ($this->room) {
                $this->room->update(['is_playing' => false]);
            }
        });

        // Relance une partie si la partie est terminée et que la partie suivante est autorisée
        ProcessRoundFinished::dispatch($this->room)
            ->delay(now()->addSeconds($this->room->pause_between_rounds));

        // Agrégation finale : ELO, standings, total_scores en batch
        ProcessRoundFinalization::dispatch($this)->afterCommit();

        broadcast(new RoundFinished($this));
        broadcast(new RoomPublicState($this->room));
    }

    public function playNextTrack()
    {
        // The round can be stopped manually
        if ($this->finished_at) {
            return;
        }

        // All tracks has been played
        if ($this->current >= count($this->tracks)) {
            $this->stop();

            return;
        }

        // Get next track ID and increment counter in single query
        $current = $this->current ?? 0;
        $trackId = $this->tracks[$current];
        $this->increment('current');

        // Eager load track with audio attribute
        $track = Track::with('answers')->find($trackId);

        if (! $track) {
            Log::error('Track not found', [
                'track_id' => $trackId,
                'round_id' => $this->id,
            ]);
            $this->playNextTrack();

            return;
        }

        if ($track->provider === 'youtube' || $track->provider === 'local') {
            // Record the timestamp when the track starts playing
            try {
                $this->update(['current_track_started_at' => now()]);
            } catch (\Exception $e) {
                // Column might not exist yet, log and continue
                Log::warning('Could not update current_track_started_at: '.$e->getMessage());
            }
            broadcast(new TrackPlayed($this, $track));
            broadcast(new RoomPublicState($this->room));
            ProcessTrackPlayed::dispatch($this)
                ->delay(now()->addSeconds($this->room->track_duration));

            return;
        }

        // Get audio URL without making additional queries
        $audioUrl = $track->audio;
        if (! $audioUrl) {
            Log::info('Missing audio URL for track', [
                'track_id' => $trackId,
                'round_id' => $this->id,
            ]);
            ProcessDeletedTrack::dispatch($track);
            $this->playNextTrack();

            return;
        }

        try {
            $response = Http::retry(3, 100)->timeout(3)->get($audioUrl);

            if ($response->successful()) {
                // Track is valid, record timestamp and broadcast
                try {
                    $this->update(['current_track_started_at' => now()]);
                } catch (\Exception $e) {
                    // Column might not exist yet, log and continue
                    Log::warning('Could not update current_track_started_at: '.$e->getMessage());
                }
                broadcast(new TrackPlayed($this, $track));
                broadcast(new RoomPublicState($this->room));
                ProcessTrackPlayed::dispatch($this)
                    ->delay(now()->addSeconds($this->room->track_duration));
            } else {
                // Handle HTTP error responses (404, 403, 500, etc.)
                ProcessDeletedTrack::dispatch($track);
                $this->playNextTrack();
            }

        } catch (ConnectionException $e) {
            // Handle connection errors (timeout, DNS failure, etc.)
            ProcessDeletedTrack::dispatch($track);
            $this->playNextTrack();
        } catch (\Exception $e) {
            // Handle other unexpected errors
            ProcessDeletedTrack::dispatch($track);
            $this->playNextTrack();
        }
    }

    public function isPlaying()
    {
        return $this->is_playing;
    }

    public function userScore(User $user): float
    {
        // Si le round est en cours, utiliser Redis (plus performant)
        if ($this->is_playing && ! $this->finished_at) {
            $roundScoreService = app(RoundScoreService::class);

            return $roundScoreService->getUserScore($this->id, $user->id);
        }

        // Sinon, utiliser les standings (déjà agrégés)
        $standing = $this->standings()->where('user_id', $user->id)->first();

        return $standing ? (float) $standing->total_score : 0.0;
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function usersPodium(): Collection
    {
        // Si le round est en cours, utiliser Redis (plus performant)
        if ($this->is_playing && ! $this->finished_at) {
            $roundScoreService = app(RoundScoreService::class);
            $podium = $roundScoreService->getPodium($this->id, 100);

            // Convertir en format compatible avec l'ancien système
            // On charge les users et teams pour avoir les relations
            $userIds = array_keys($podium);
            $users = User::whereIn('id', $userIds)->with('userLevel', 'team')->get()->keyBy('id');

            return collect($podium)->map(function ($total, $userId) use ($users) {
                $user = $users->get($userId);

                return (object) [
                    'user_id' => $userId,
                    'total' => $total,
                    'team_id' => $user?->team?->id,
                    'user' => $user,
                ];
            })->values();
        }

        // Si le round est terminé mais que les standings n'existent pas encore,
        // utiliser Redis (le job ProcessRoundFinalization n'a peut-être pas encore terminé)
        if ($this->finished_at && ! $this->standings()->exists()) {
            $roundScoreService = app(RoundScoreService::class);
            $podium = $roundScoreService->getPodium($this->id, 100);

            // Convertir en format compatible avec l'ancien système
            $userIds = array_keys($podium);
            $users = User::whereIn('id', $userIds)->with('userLevel', 'team')->get()->keyBy('id');

            return collect($podium)->map(function ($total, $userId) use ($users) {
                $user = $users->get($userId);

                return (object) [
                    'user_id' => $userId,
                    'total' => $total,
                    'team_id' => $user?->team?->id,
                    'user' => $user,
                ];
            })->values();
        }

        // Sinon, utiliser les standings (déjà agrégés)
        return $this->standings()
            ->select([
                'user_id',
                'total_score as total',
                'team_id',
            ])
            ->with('user.userLevel')
            ->orderByDesc('total_score')
            ->get();
    }

    public function teamsPodium(): Collection
    {
        // Si le round est en cours, utiliser Redis (plus performant)
        if ($this->is_playing && ! $this->finished_at) {
            $roundScoreService = app(RoundScoreService::class);
            $podium = $roundScoreService->getPodium($this->id, 100);

            // Convertir en format compatible avec l'ancien système
            $userIds = array_keys($podium);
            $users = User::whereIn('id', $userIds)->with('team')->get()->keyBy('id');

            // Grouper par équipe
            $teamsScores = [];
            foreach ($podium as $userId => $total) {
                $user = $users->get($userId);
                if ($user && $user->team) {
                    $teamId = $user->team->id;
                    if (! isset($teamsScores[$teamId])) {
                        $teamsScores[$teamId] = [
                            'team_id' => $teamId,
                            'total' => 0,
                            'team' => $user->team,
                        ];
                    }
                    $teamsScores[$teamId]['total'] += $total;
                }
            }

            return collect($teamsScores)->sortByDesc('total')->values();
        }

        // Si le round est terminé mais que les standings n'existent pas encore,
        // utiliser Redis (le job ProcessRoundFinalization n'a peut-être pas encore terminé)
        if ($this->finished_at && ! $this->standings()->exists()) {
            $roundScoreService = app(RoundScoreService::class);
            $podium = $roundScoreService->getPodium($this->id, 100);

            // Convertir en format compatible avec l'ancien système
            $userIds = array_keys($podium);
            $users = User::whereIn('id', $userIds)->with('team')->get()->keyBy('id');

            // Grouper par équipe
            $teamsScores = [];
            foreach ($podium as $userId => $total) {
                $user = $users->get($userId);
                if ($user && $user->team) {
                    $teamId = $user->team->id;
                    if (! isset($teamsScores[$teamId])) {
                        $teamsScores[$teamId] = [
                            'team_id' => $teamId,
                            'total' => 0,
                            'team' => $user->team,
                        ];
                    }
                    $teamsScores[$teamId]['total'] += $total;
                }
            }

            return collect($teamsScores)->sortByDesc('total')->values();
        }

        // Sinon, utiliser les standings (déjà agrégés)
        return $this->standings()
            ->whereNotNull('team_id')
            ->select([\DB::raw('SUM(total_score) as total'), 'team_id'])
            ->with('team')
            ->groupBy('team_id')
            ->orderByDesc('total')
            ->get();
    }

    public function standings(): HasMany
    {
        return $this->hasMany(RoundStanding::class);
    }
}
