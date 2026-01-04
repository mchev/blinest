<?php

namespace Tests\Feature;

use App\Events\UserEloUpdated;
use App\Jobs\ProcessRoundFinalization;
use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Round;
use App\Models\RoundStanding;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use App\Services\RoundScoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ProcessRoundFinalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Nettoyer Redis avant chaque test
        Redis::flushall();
        // Mocker les jobs pour éviter les erreurs de broadcast dans UpdateUserLevel
        Bus::fake();
    }

    protected function tearDown(): void
    {
        // Nettoyer Redis après chaque test
        Redis::flushall();
        parent::tearDown();
    }

    /**
     * Test que les standings sont créés avec l'historique des tracks depuis Redis
     */
    public function test_public_room_with_three_players_creates_standings_with_tracks_history(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'elo' => 1500,
        ]);
        $room = Room::create([
            'name' => 'Test Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $users = User::factory()->count(3)->create(['elo' => 1500]);

        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        // Créer 10 tracks pour le round
        $tracks = [];
        for ($i = 0; $i < 10; $i++) {
            $track = Track::create([
                'playlist_id' => $playlist->id,
                'user_id' => $owner->id,
                'provider' => 'youtube',
                'provider_id' => "test123_{$i}",
                'preview_url' => 'https://example.com/preview',
                'artwork_url' => 'https://example.com/artwork',
            ]);
            $trackAnswer = TrackAnswer::create([
                'track_id' => $track->id,
                'answer_type_id' => $answerType->id,
                'value' => "Test Artist {$i}",
                'score' => 1.0,
            ]);
            $tracks[] = ['track' => $track, 'answer' => $trackAnswer];
        }

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
            'tracks' => collect($tracks)->pluck('track.id')->toArray(),
        ]);

        // Simuler les scores dans Redis
        $roundScoreService = app(RoundScoreService::class);
        foreach ($users as $index => $user) {
            foreach ($tracks as $trackData) {
                $scoreValue = 10.0 - ($index * 2);
                $responseTime = 5.0 + ($index * 2);

                // Ajouter le score
                $roundScoreService->addScore($round->id, $user->id, $scoreValue);

                // Enregistrer les détails de la track
                $roundScoreService->recordTrackDetails(
                    $round->id,
                    $user->id,
                    $trackData['track']->id,
                    $responseTime,
                    null, // position sera calculée
                    $scoreValue,
                    $trackData['answer']->id // answer_id
                );
            }
        }

        // Exécuter le job
        $job = new ProcessRoundFinalization($round);
        $job->handle($roundScoreService, app(\App\Services\EloService::class));

        // Vérifier que les standings ont été créés
        $standings = RoundStanding::where('round_id', $round->id)->get();
        $this->assertCount(3, $standings);

        // Vérifier que chaque standing a un historique de tracks
        foreach ($standings as $standing) {
            $this->assertNotNull($standing->tracks_history);
            $this->assertIsArray($standing->tracks_history);
            $this->assertCount(10, $standing->tracks_history); // 10 tracks

            // Vérifier la structure de chaque entrée dans l'historique
            foreach ($standing->tracks_history as $trackHistory) {
                $this->assertArrayHasKey('track_id', $trackHistory);
                $this->assertArrayHasKey('answer_id', $trackHistory);
                $this->assertArrayHasKey('response_time', $trackHistory);
                $this->assertArrayHasKey('position', $trackHistory);
                $this->assertArrayHasKey('score', $trackHistory);
            }
        }

        // Vérifier que is_elo_counted = true pour tous
        foreach ($standings as $standing) {
            $this->assertTrue($standing->is_elo_counted);
        }

        // Vérifier que les ELO ont été mis à jour
        foreach ($users as $user) {
            $user->refresh();
            $this->assertNotEquals(1500, $user->elo, "ELO should have changed for user {$user->id}");
        }

        // Vérifier les positions
        $firstStanding = $standings->where('user_id', $users[0]->id)->first();
        $this->assertEquals(1, $firstStanding->position);
        $this->assertEquals(100.0, (float) $firstStanding->total_score);
    }

    /**
     * Test que les standings sont créés mais is_elo_counted = false pour une room privée
     */
    public function test_private_room_creates_standings_without_elo_update(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner2@test.com',
            'password' => bcrypt('password'),
            'elo' => 1500,
        ]);
        $room = Room::create([
            'name' => 'Private Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => false,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $users = User::factory()->count(3)->create(['elo' => 1500]);
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
            'tracks' => [1, 2, 3],
        ]);

        $roundScoreService = app(RoundScoreService::class);
        foreach ($users as $user) {
            $roundScoreService->addScore($round->id, $user->id, 5.0);
            $roundScoreService->recordTrackDetails($round->id, $user->id, 1, 10.0, null, 5.0, null);
        }

        $job = new ProcessRoundFinalization($round);
        $job->handle($roundScoreService, app(\App\Services\EloService::class));

        $standings = RoundStanding::where('round_id', $round->id)->get();
        $this->assertCount(3, $standings);

        foreach ($standings as $standing) {
            $this->assertFalse($standing->is_elo_counted);
        }

        foreach ($users as $user) {
            $user->refresh();
            $this->assertEquals(1500, $user->elo);
        }
    }

    /**
     * Test qu'un joueur qui rejoint en cours de partie n'a pas son ELO compté s'il n'a pas joué assez de tracks
     */
    public function test_player_joining_mid_round_without_enough_tracks_does_not_count_elo(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner3@test.com',
            'password' => bcrypt('password'),
            'elo' => 1500,
        ]);
        $room = Room::create([
            'name' => 'Test Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $fullPlayers = User::factory()->count(3)->create(['elo' => 1500]);
        $partialPlayer = User::factory()->create(['elo' => 1500]);

        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $tracks = [];
        for ($i = 0; $i < 10; $i++) {
            $track = Track::create([
                'playlist_id' => $playlist->id,
                'user_id' => $owner->id,
                'provider' => 'youtube',
                'provider_id' => "test{$i}",
                'preview_url' => 'https://example.com/preview',
                'artwork_url' => 'https://example.com/artwork',
            ]);
            $tracks[] = $track;
        }

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
            'tracks' => collect($tracks)->pluck('id')->toArray(),
        ]);

        $roundScoreService = app(RoundScoreService::class);

        // Les 3 joueurs complets jouent toutes les 10 tracks
        foreach ($fullPlayers as $fullPlayer) {
            foreach ($tracks as $track) {
                $roundScoreService->addScore($round->id, $fullPlayer->id, 1.0);
                $roundScoreService->recordTrackDetails($round->id, $fullPlayer->id, $track->id, 10.0, null, 1.0, null);
            }
        }

        // Le joueur partiel ne joue que les 2 dernières tracks (20% < 80%)
        foreach (array_slice($tracks, -2) as $track) {
            $roundScoreService->addScore($round->id, $partialPlayer->id, 1.0);
            $roundScoreService->recordTrackDetails($round->id, $partialPlayer->id, $track->id, 10.0, null, 1.0, null);
        }

        $job = new ProcessRoundFinalization($round);
        $job->handle($roundScoreService, app(\App\Services\EloService::class));

        $standings = RoundStanding::where('round_id', $round->id)->get();
        $this->assertCount(4, $standings);

        foreach ($fullPlayers as $fullPlayer) {
            $standing = $standings->where('user_id', $fullPlayer->id)->first();
            $this->assertTrue($standing->is_elo_counted);
            $this->assertCount(10, $standing->tracks_history);
        }

        $partialStanding = $standings->where('user_id', $partialPlayer->id)->first();
        $this->assertFalse($partialStanding->is_elo_counted);
        $this->assertCount(2, $partialStanding->tracks_history);
    }

    /**
     * Test que l'événement UserEloUpdated est broadcasté
     */
    public function test_user_elo_updated_event_is_broadcasted(): void
    {
        Event::fake([UserEloUpdated::class]);

        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner4@test.com',
            'password' => bcrypt('password'),
            'elo' => 1500,
        ]);
        $room = Room::create([
            'name' => 'Test Room',
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'is_public' => true,
            'is_featured' => false,
            'track_duration' => 30,
            'tracks_by_round' => 10,
        ]);

        $users = User::factory()->count(3)->create(['elo' => 1500]);

        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $tracks = [];
        for ($i = 0; $i < 10; $i++) {
            $track = Track::create([
                'playlist_id' => $playlist->id,
                'user_id' => $owner->id,
                'provider' => 'youtube',
                'provider_id' => "test606_{$i}",
                'preview_url' => 'https://example.com/preview',
                'artwork_url' => 'https://example.com/artwork',
            ]);
            $tracks[] = $track;
        }

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
            'tracks' => collect($tracks)->pluck('id')->toArray(),
        ]);

        $roundScoreService = app(RoundScoreService::class);
        foreach ($users as $index => $user) {
            foreach ($tracks as $track) {
                $scoreValue = 10.0 - ($index * 2);
                $roundScoreService->addScore($round->id, $user->id, $scoreValue);
                $roundScoreService->recordTrackDetails($round->id, $user->id, $track->id, 5.0, null, $scoreValue, null);
            }
        }

        $job = new ProcessRoundFinalization($round);
        $job->handle($roundScoreService, app(\App\Services\EloService::class));

        Event::assertDispatched(UserEloUpdated::class, 3);
    }
}
