<?php

namespace Tests\Feature;

use App\Models\AnswerType;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaylistCsvExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_export_playlist_as_csv(): void
    {
        [$owner, $playlist, $artistType, $titleType] = $this->createPlaylistWithTrack();

        $response = $this->actingAs($owner)
            ->get(route('playlists.export', $playlist));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();

        $this->assertStringContainsString(__($artistType->name), $content);
        $this->assertStringContainsString(__($titleType->name), $content);
        $this->assertStringContainsString('Daft Punk', $content);
        $this->assertStringContainsString('One More Time', $content);
        $this->assertStringContainsString('deezer', $content);
        $this->assertStringContainsString('https://www.deezer.com/track/123', $content);
    }

    public function test_moderator_can_export_playlist_as_csv(): void
    {
        [$owner, $playlist] = $this->createPlaylistWithTrack();
        $moderator = User::factory()->create(['email_verified_at' => now()]);
        $playlist->moderators()->attach($moderator->id);

        $this->actingAs($moderator)
            ->get(route('playlists.export', $playlist))
            ->assertOk();
    }

    public function test_unauthorized_user_cannot_export_playlist(): void
    {
        [, $playlist] = $this->createPlaylistWithTrack();
        $stranger = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($stranger)
            ->get(route('playlists.export', $playlist))
            ->assertForbidden();
    }

    /**
     * @return array{0: User, 1: Playlist, 2: AnswerType, 3: AnswerType}
     */
    private function createPlaylistWithTrack(): array
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);

        $artistType = AnswerType::query()->create([
            'name' => 'Artist',
            'pronoun' => 'the',
        ]);

        $titleType = AnswerType::query()->create([
            'name' => 'Title',
            'pronoun' => 'the',
        ]);

        $playlist = Playlist::query()->create([
            'name' => 'Export Playlist',
            'user_id' => $owner->id,
        ]);

        $playlist->moderators()->attach($owner->id);

        $track = Track::query()->create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'deezer',
            'provider_id' => '123',
            'provider_url' => 'https://www.deezer.com/track/123',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/art',
            'dificulty' => 1,
        ]);

        TrackAnswer::query()->create([
            'track_id' => $track->id,
            'answer_type_id' => $artistType->id,
            'value' => 'Daft Punk',
        ]);

        TrackAnswer::query()->create([
            'track_id' => $track->id,
            'answer_type_id' => $titleType->id,
            'value' => 'One More Time',
        ]);

        return [$owner, $playlist, $artistType, $titleType];
    }
}
