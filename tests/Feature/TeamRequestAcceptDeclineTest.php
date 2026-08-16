<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\TeamRequest;
use App\Models\User;
use App\Notifications\NewTeamRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TeamRequestAcceptDeclineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{owner: User, applicant: User, team: Team, teamRequest: TeamRequest}
     */
    private function createTeamJoinRequest(): array
    {
        $owner = User::factory()->create();
        $applicant = User::factory()->create();
        $team = Team::create([
            'name' => 'Join Team',
            'user_id' => $owner->id,
        ]);
        $owner->update(['team_id' => $team->id]);

        $teamRequest = TeamRequest::create([
            'team_id' => $team->id,
            'user_id' => $applicant->id,
        ]);

        Notification::send($owner, new NewTeamRequest($teamRequest));

        return compact('owner', 'applicant', 'team', 'teamRequest');
    }

    public function test_captain_can_accept_team_request(): void
    {
        ['owner' => $owner, 'applicant' => $applicant, 'teamRequest' => $teamRequest] = $this->createTeamJoinRequest();

        $notification = $owner->fresh()->unreadNotifications()->first();
        $this->assertNotNull($notification);
        $this->assertSame($teamRequest->id, $notification->data['teamRequest']['id']);

        $response = $this->actingAs($owner)->post("/teams/requests/{$teamRequest->id}/accept", [
            'notification_id' => $notification->id,
        ]);

        $response->assertRedirect();
        $this->assertSame($teamRequest->team_id, $applicant->fresh()->team_id);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_captain_can_decline_team_request(): void
    {
        ['owner' => $owner, 'applicant' => $applicant, 'teamRequest' => $teamRequest] = $this->createTeamJoinRequest();

        $notification = $owner->fresh()->unreadNotifications()->first();

        $response = $this->actingAs($owner)->post("/teams/requests/{$teamRequest->id}/decline", [
            'notification_id' => $notification->id,
        ]);

        $response->assertRedirect();
        $this->assertNull($applicant->fresh()->team_id);
        $this->assertNotNull($teamRequest->fresh()->declined_at);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_non_captain_cannot_accept_team_request(): void
    {
        ['owner' => $owner, 'applicant' => $applicant, 'teamRequest' => $teamRequest] = $this->createTeamJoinRequest();

        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->post("/teams/requests/{$teamRequest->id}/accept")
            ->assertForbidden();

        $this->assertNull($applicant->fresh()->team_id);
    }

    public function test_accept_clears_owner_notification_cache(): void
    {
        ['owner' => $owner, 'teamRequest' => $teamRequest] = $this->createTeamJoinRequest();

        $notification = $owner->fresh()->unreadNotifications()->first();
        Cache::forever("{$owner->id}_unread_notifications", $owner->unreadNotifications->map->toArray()->values()->all());

        $this->actingAs($owner)->post("/teams/requests/{$teamRequest->id}/accept", [
            'notification_id' => $notification->id,
        ]);

        $this->assertFalse(Cache::has("{$owner->id}_unread_notifications"));
    }
}
