<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserPhotoRequest;
use App\Http\Requests\UpdateDonationPreferencesRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserPhotoRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use App\Services\Account\AccountPageService;
use App\Services\BrevoService;
use App\Services\Profiles\ProfileCacheService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Laravel\Head\Facades\Head;

class UserController extends Controller
{
    public function __construct(
        private AccountPageService $account,
    ) {}

    public function show()
    {
        $user = Auth::user();

        Head::title(__('My account'));

        return Inertia::render('Me/Show', [
            'account' => $this->account->payload($user),
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

    public function update(UpdateUserProfileRequest $request, User $user)
    {
        $user->update($request->validated());

        return Redirect::back()->with('success', __('Information updated'));
    }

    public function updateDonationPreferences(UpdateDonationPreferencesRequest $request, User $user)
    {
        $user->update($request->validated());

        app(ProfileCacheService::class)->forget($user);

        return Redirect::back()->with('success', __('Donation preferences updated'));
    }

    public function updatePhoto(UpdateUserPhotoRequest $request, User $user)
    {
        $user->updatePhoto($request->file('photo'));

        return Redirect::back()->with('success', __('Avatar updated'));
    }

    public function destroyPhoto(DeleteUserPhotoRequest $request, User $user)
    {
        $user->deletePhoto();

        return Redirect::back()->with('success', __('Avatar removed'));
    }

    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $user->update([
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        return Redirect::back()->with('success', __('Password updated'));
    }

    public function destroy(User $user)
    {
        if (Auth::user()->id === $user->id || Auth::user()->isAdministrator()) {
            (new BrevoService)->contacts()->delete($user);
            $user->deletePhoto();
            $user->rooms()->delete();
            $user->playlists()->delete();
            $user->scores()->delete();
            $user->totalScores()->delete();
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
