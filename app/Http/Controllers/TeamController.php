<?php

namespace App\Http\Controllers;

use App\Models\RoundStanding;
use App\Models\Team;
use App\Models\TotalScore;
use App\Models\User;
use App\Rules\Reserved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $publicRankingScores = TotalScore::query()
            ->selectRaw('total_scores.totalscorable_id as team_id')
            ->selectRaw('ROUND(SUM(total_scores.score), 1) as public_rank_score')
            ->where('total_scores.totalscorable_type', Team::class)
            ->join('rooms', 'rooms.id', '=', 'total_scores.room_id')
            ->where('rooms.is_public', true)
            ->groupBy('total_scores.totalscorable_id');

        $teams = Team::query()
            ->select('teams.*')
            ->selectRaw('COALESCE(team_ranking.public_rank_score, 0) as team_points')
            ->leftJoinSub($publicRankingScores, 'team_ranking', 'teams.id', '=', 'team_ranking.team_id')
            ->whereHas('owner', function ($query) {
                $query->notBanned();
            })
            ->filter($request->only('search'))
            ->with('owner')
            ->withCount('members')
            ->orderByDesc(DB::raw('COALESCE(team_ranking.public_rank_score, 0)'))
            ->orderBy('teams.name')
            ->paginate(12)
            ->withQueryString();

        $roundCounts = RoundStanding::query()
            ->whereIn('team_id', $teams->pluck('id'))
            ->selectRaw('team_id, COUNT(DISTINCT round_id) as rounds_played')
            ->groupBy('team_id')
            ->pluck('rounds_played', 'team_id');

        $teams->getCollection()->transform(function (Team $team) use ($roundCounts) {
            $team->rounds_played = (int) ($roundCounts[$team->id] ?? 0);
            $points = (float) ($team->team_points ?? 0);
            $team->team_points = $points;
            $team->team_points_abbreviated = Number::abbreviate($points, 1, 2);

            return $team;
        });

        return Inertia::render('Teams/Index', [
            'filters' => $request->all('search'),
            'teams' => $teams,
        ]);
    }

    public function create()
    {
        return Auth::user()->hasTeam()
            ? redirect()->back()->with('error', __('You are already part of a team'))
            : Inertia::render('Teams/Create');
    }

    public function switchOwner(Team $team, User $user)
    {
        if ($team?->owner?->id === Auth::user()->id) {
            $team->update([
                'user_id' => $user->id,
            ]);

            return redirect()->route('teams.show', $team)->with('success', __('The owner of the team has been updated'));
        }

        return abort(403, __('Unauthorized action'));
    }

    public function removeMember(Team $team, User $user)
    {
        if ($team?->owner?->id === Auth::user()->id) {
            $user->team_id = null;
            $user->update();

            return redirect()->route('teams.show', $team)->with('success', __('The member is not part of the team anymore'));
        }

        return abort(403, __('Unauthorized action'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasTeam()) {
            redirect()->back()->with('error', __('You are already part of a team'));
        }

        $request->validate([
            'name' => ['required', 'max:30', 'unique:teams', 'alpha_dash', new Reserved],
        ]);

        $team = Team::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
        ]);

        Auth::user()->update([
            'team_id' => $team->id,
        ]);

        return redirect()->route('teams.show', $team);
    }

    public function show(Team $team)
    {
        $team->load('owner');
        $memberIds = $team->members->pluck('id');

        $teamAggregate = RoundStanding::query()
            ->where('team_id', $team->id)
            ->selectRaw('COUNT(DISTINCT round_id) as rounds_played, MAX(created_at) as last_played_at, COALESCE(SUM(total_score), 0) as team_points')
            ->first();

        $roundsPlayed = (int) ($teamAggregate->rounds_played ?? 0);
        $teamScore = (float) ($teamAggregate->team_points ?? 0);

        $memberRows = RoundStanding::query()
            ->where('team_id', $team->id)
            ->whereIn('user_id', $memberIds)
            ->selectRaw('user_id, SUM(total_score) as total_score, COUNT(DISTINCT round_id) as rounds_played')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $avgPerRound = $roundsPlayed > 0
            ? round($teamScore / $roundsPlayed, 1)
            : 0.0;

        return Inertia::render('Teams/Show', [
            'team' => $team,
            'score' => $teamScore,
            'stats' => [
                'rounds_played' => $roundsPlayed,
                'last_played_at' => $teamAggregate->last_played_at,
                'avg_points_per_round' => $avgPerRound,
            ],
            'members' => $team->members->map(function ($member) use ($memberRows, $teamScore) {
                $row = $memberRows->get($member->id);
                $score = (float) ($row->total_score ?? 0);
                $played = (int) ($row->rounds_played ?? 0);

                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'photo' => $member->photo,
                    'score' => $score,
                    'rounds_played' => $played,
                    'contribution_percent' => $teamScore > 0
                        ? round(($score / $teamScore) * 100, 1)
                        : 0.0,
                ];
            })->sortByDesc('score')->values(),
        ]);
    }

    public function update(Request $request, Team $team)
    {
        if (Auth::user()->id === $team->user_id) {
            $request->validate([
                'name' => ['required', 'max:30', Rule::unique('teams')->ignore($team->id), 'alpha_dash', new Reserved],
                'photo' => ['nullable', 'image'],
            ]);

            $team->update($request->only('name'));

            if ($request->file('photo')) {
                $team->updatePhoto($request->file('photo'));
            }

            return redirect()->back()->with('success', __('Updated'));
        }

        return redirect()->back()->with('error', __('This is not your team'));
    }

    public function leave(Team $team)
    {
        if (Auth::user()->id === $team->user_id) {
            return redirect()->back()->with('error', __('Impossible to leave your own team. You must transfer ownership to another member first'));
        }

        if ($team->members()->where('id', Auth::user()->id)->exists()) {
            $user = Auth::user();
            $user->team()->dissociate();
            $user->save();

            return redirect()->route('teams.index')->with('success', __('You have left the team').' '.$team->name);
        } else {
            return redirect()->back()->with('error', __('You are not part of this team'));
        }
    }

    public function destroy(Team $team)
    {
        if (Auth::user()->id === $team->user_id && $team->members->count() === 1) {
            $team->members()->update(['team_id' => null]);
            $team->delete();

            return redirect()->route('teams.index')->with('success', __('The Team').' '.$team->name.' '.__('has been deleted'));
        }

        return redirect()->back()->with('error', __('This is not your team'));
    }
}
