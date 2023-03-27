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
            return redirect()->route('teams.index')->with('error', __('It is not possible to join another team. You must leave the current team and make a new request'));
        }

        // Max 3 team requests by user
        if (Auth::user()->teamRequests()->count() > 2) {
            return redirect()->back()->with('error', __('You cannot do more than 3 requests at the same time'));
        }

        // Check if all the seats are occupied
        if ($team->seats > $team->members()->count()) {
            $teamRequest = Auth::user()->teamRequests()->create([
                'team_id' => $team->id,
            ]);

            $team->owner->notify(new NewTeamRequest($teamRequest));

            return redirect()->back()->with('success', __('The request has been sent'));
        }

        return redirect()->back()->with('error', __('It is not possible to join this team. The maximum number of members has been reached'));
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

        return abort(403, __('Unauthorized action'));
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

        return abort(403, __('Unauthorized action'));
    }

    public function cancel(Team $team)
    {
        $teamRequest = Auth::user()->teamRequests()->where('team_id', $team->id)->first();

        if ($teamRequest) {
            $teamRequest->cancel();

            return redirect()->back()->with('success', 'The request has been cancelled');
        }

        return abort(403, __('Unauthorized action'));
    }
}
