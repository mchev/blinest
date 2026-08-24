<?php

namespace App\Services\Auth;

use App\Enums\FacebookDataDeletionAction;
use App\Jobs\ProcessFacebookDataDeletion;
use App\Models\FacebookDataDeletionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class FacebookDataDeletionService
{
    public function __construct(
        private UserAccountDeletionService $accountDeletion,
    ) {}

    public function queue(string $facebookUserId, string $source = 'callback'): FacebookDataDeletionRequest
    {
        $request = FacebookDataDeletionRequest::query()->create([
            'confirmation_code' => $this->generateConfirmationCode(),
            'facebook_user_id' => $facebookUserId,
            'action' => FacebookDataDeletionAction::Pending,
            'source' => $source,
        ]);

        ProcessFacebookDataDeletion::dispatch($request);

        return $request;
    }

    public function process(FacebookDataDeletionRequest $request): FacebookDataDeletionRequest
    {
        if ($request->isProcessed()) {
            return $request;
        }

        try {
            $user = $this->findFacebookUser($request->facebook_user_id);

            if ($user === null) {
                $request->update([
                    'action' => FacebookDataDeletionAction::NotFound,
                    'processed_at' => now(),
                ]);

                return $request->fresh();
            }

            $request->update(['user_id' => $user->id]);

            if ($user->hasPassword()) {
                $this->accountDeletion->unlinkFacebook($user);

                $request->update([
                    'action' => FacebookDataDeletionAction::Unlinked,
                    'processed_at' => now(),
                ]);
            } else {
                $this->accountDeletion->delete($user);

                $request->update([
                    'action' => FacebookDataDeletionAction::Deleted,
                    'processed_at' => now(),
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Facebook data deletion failed', [
                'facebook_user_id' => $request->facebook_user_id,
                'confirmation_code' => $request->confirmation_code,
                'error' => $exception->getMessage(),
            ]);

            $request->update([
                'action' => FacebookDataDeletionAction::Failed,
                'processed_at' => now(),
                'error_message' => $exception->getMessage(),
            ]);
        }

        return $request->fresh();
    }

    /**
     * @return array{processed: int, deleted: int, unlinked: int, not_found: int, failed: int}
     */
    public function processIds(array $facebookUserIds, string $source = 'manual', bool $dryRun = false): array
    {
        $summary = [
            'processed' => 0,
            'deleted' => 0,
            'unlinked' => 0,
            'not_found' => 0,
            'failed' => 0,
        ];

        foreach ($facebookUserIds as $facebookUserId) {
            $facebookUserId = trim((string) $facebookUserId);

            if ($facebookUserId === '') {
                continue;
            }

            $summary['processed']++;

            if ($dryRun) {
                $user = $this->findFacebookUser($facebookUserId);

                if ($user === null) {
                    $summary['not_found']++;
                } elseif ($user->hasPassword()) {
                    $summary['unlinked']++;
                } else {
                    $summary['deleted']++;
                }

                continue;
            }

            $request = FacebookDataDeletionRequest::query()->create([
                'confirmation_code' => $this->generateConfirmationCode(),
                'facebook_user_id' => $facebookUserId,
                'action' => FacebookDataDeletionAction::Pending,
                'source' => $source,
            ]);

            $request = $this->process($request);

            match ($request->action) {
                FacebookDataDeletionAction::Deleted => $summary['deleted']++,
                FacebookDataDeletionAction::Unlinked => $summary['unlinked']++,
                FacebookDataDeletionAction::NotFound => $summary['not_found']++,
                FacebookDataDeletionAction::Failed => $summary['failed']++,
                default => null,
            };
        }

        return $summary;
    }

    public function statusUrl(FacebookDataDeletionRequest $request): string
    {
        return route('facebook.data-deletion.status', $request->confirmation_code);
    }

    private function findFacebookUser(string $facebookUserId): ?User
    {
        return User::query()
            ->where('provider', 'facebook')
            ->where('provider_id', $facebookUserId)
            ->first();
    }

    private function generateConfirmationCode(): string
    {
        do {
            $code = Str::upper(Str::random(12));
        } while (FacebookDataDeletionRequest::query()->where('confirmation_code', $code)->exists());

        return $code;
    }
}
