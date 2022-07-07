<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamRequest;
use App\Notifications\NewTeamRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class TeamRequestController extends Controller
{
    public function store(Team $team)
    {

        // User is not already in a team
        if (Auth::user()->hasTeam()) {
            return redirect()->route('teams.index')->with('error', "Il n'est pas possible de rejoindre une autre team. Il faut quitter la team actuelle pour faire une nouvelle demande.");
        }

        // Max 3 team requests by user
        if (Auth::user()->teamRequests()->count() > 2) {
            return redirect()->back()->with('error', 'Tu ne peux pas faire plus de 3 demandes à la fois.');
        }

        // Check if all the seats are occupied
        if ($team->seats > $team->members()->count()) {
            $teamRequest = Auth::user()->teamRequests()->create([
                'team_id' => $team->id,
            ]);

            $team->owner->notify(new NewTeamRequest($teamRequest));

            return redirect()->back()->with('success', 'La demande a été envoyée.');
        }

        return redirect()->back()->with('error', 'Impossible de rejoindre cette team. Le nombre maximum de membres a été atteint.');
    }

    public function accept(TeamRequest $teamRequest)
    {
        if (Auth::user()->id === $teamRequest->team->owner->id) {
            $teamRequest->approve();

            if (Request::has('notification_id')) {
                Auth::user()->notifications()->where('id', Request::input('notification_id'))->update([
                    'read_at' => now(),
                ]);
            }

            return redirect()->back();
        }

        return abort(403, 'Unauthorized action.');
    }

    public function decline(TeamRequest $teamRequest)
    {
        if (Auth::user()->id === $teamRequest->team->owner->id) {
            $teamRequest->reject();

            if (Request::has('notification_id')) {
                Auth::user()->notifications()->where('id', Request::input('notification_id'))->update([
                    'read_at' => now(),
                ]);
            }

            return redirect()->back();
        }

        return abort(403, 'Unauthorized action.');
    }

    public function cancel(Team $team)
    {
        $teamRequest = Auth::user()->teamRequests()->where('team_id', $team->id)->first();

        if ($teamRequest) {
            $teamRequest->cancel();

            return redirect()->back()->with('success', 'La demande a été annulée.');
        }

        return abort(403, 'Unauthorized action.');
    }
}
