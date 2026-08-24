<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\BrevoService;

class UserAccountDeletionService
{
    public function delete(User $user): void
    {
        (new BrevoService)->contacts()->delete($user);
        $user->deletePhoto();
        $user->rooms()->delete();
        $user->playlists()->delete();
        $user->scores()->delete();
        $user->totalScores()->delete();
        $user->forceDelete();
    }

    public function unlinkFacebook(User $user): void
    {
        $user->update([
            'provider' => null,
            'provider_id' => null,
        ]);
    }
}
