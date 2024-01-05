<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Rules\Reserved;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return Inertia::render('Me/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo,
                'created_at' => $user->created_at->format('d/m/Y H:i'),
                'created_at_from_now' => $user->created_at->diffForHumans(),
                'latest_round_at' => $user->totalScores()->latest()->first()?->updated_at->format('d/m/Y H:i'),
                'rooms' => $user->moderatedRooms,
                'playlists' => $user->moderatedPlaylists,
                'bookmarked_rooms' => $user->bookmarkedRooms()->get(),
                'total_score' => $user->totalScores()->sum('score'),
                'scores' => $user->totalScores()->whereHas('room')->with('room')->paginate(10)->through(fn ($score) => [
                    'room_id' => $score->room->id,
                    'room_slug' => $score->room->slug,
                    'name' => $score->room->name,
                    'date' => $score->updated_at->diffForHumans(),
                    'total' => $score->score,
                    'max' => $user->maxScoreByRoom(Room::find($score->room->id))->first()?->total ?? '-',
                ]),
            ],
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->id === $user->id) {
            $request->validate([
                'name' => ['required', 'max:25', Rule::unique('users')->ignore($user->id), new Reserved],
                'email' => ['required', 'max:255', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable', Rules\Password::defaults()],
                'photo' => ['nullable', 'image'],
            ]);

            $user->update($request->only('name', 'email'));

            if ($request->file('photo')) {
                $user->updatePhoto($request->file('photo'));
            }

            if ($request->get('password')) {
                $user->update(['password' => Hash::make($request->get('password'))]);
            }

            return Redirect::back()->with('success', __('Information updated'));
        }
    }

    public function destroy(User $user)
    {
        if (Auth::user()->id === $user->id || Auth::user()->isAdministrator()) {
            (new BrevoService)->contacts()->delete($user);
            $user->deletePhoto();
            $user->forceDelete();
            Session::flush();
            Auth::logout();

            return redirect('login');
        } else {
            abort(403, __('Unauthorized action'));
        }
    }

    public function markNotificationAsRead(Request $request, $id)
    {
        $request->user()->notifications()->find($id)->markAsRead();
        Cache::forget($request->user()->id.'_unread_notifications');

        return Redirect::back();
    }

    public function markNotificationAsDone(Request $request, $id)
    {
        $done = $request->user()->notifications()->find($id);

        DatabaseNotification::where('type', $done->type)->whereNull('read_at')->get()->each(function ($notification) use ($done) {
            if ($notification->data['message'] == $done->data['message']) {
                $notification->markAsRead();
            }
        });

        Cache::forget($request->user()->id.'_unread_notifications');

        return Redirect::back();
    }
}
