<?php

namespace Tests\Feature;

use App\Jobs\ProcessRoundElo;
use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Round;
use App\Models\RoundStanding;
use App\Models\Score;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessRoundEloTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que les standings sont créés et les scores nettoyés pour une room publique avec 3+ joueurs
     */
    public function test_public_room_with_three_players_creates_standings_and_cleans_scores(): void
    {
        // Créer les données de base
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
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
        ]);

        // Créer un track avec des answers (champs minimaux)
        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'test123',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        // Créer des scores pour chaque joueur
        $scoresData = [];
        foreach ($users as $index => $user) {
            // Le premier joueur a le meilleur score
            $scoreValue = 10.0 - ($index * 2);
            $time = 5.0 + ($index * 2); // Temps différents pour tester les métriques

            $scoresData[] = [
                'user_id' => $user->id,
                'round_id' => $round->id,
                'track_id' => $track->id,
                'answer_id' => $trackAnswer->id,
                'score' => $scoreValue,
                'time' => $time,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Score::insert($scoresData);

        // Vérifier qu'il y a bien 3 scores
        $this->assertEquals(3, Score::where('round_id', $round->id)->count());

        // Exécuter le job
        $job = new ProcessRoundElo($round);
        $job->handle(app(\App\Services\EloService::class));

        // Vérifier que les standings ont été créés
        $standings = RoundStanding::where('round_id', $round->id)->get();
        $this->assertCount(3, $standings);

        // Vérifier que les scores individuels ont été supprimés
        $this->assertEquals(0, Score::where('round_id', $round->id)->count());

        // Vérifier que is_elo_counted = true pour tous
        foreach ($standings as $standing) {
            $this->assertTrue($standing->is_elo_counted);
        }

        // Vérifier que les ELO ont été mis à jour
        foreach ($users as $index => $user) {
            $user->refresh();
            $this->assertNotEquals(1500, $user->elo, "ELO should have changed for user {$user->id}");
        }

        // Vérifier les positions (le premier joueur devrait être 1er)
        $firstStanding = $standings->where('user_id', $users[0]->id)->first();
        $this->assertEquals(1, $firstStanding->position);
        $this->assertEquals(10.0, (float) $firstStanding->total_score);
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
        ]);

        $playlist = \App\Models\Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'test456',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        // Créer des scores
        foreach ($users as $user) {
            Score::create([
                'user_id' => $user->id,
                'round_id' => $round->id,
                'track_id' => $track->id,
                'answer_id' => $trackAnswer->id,
                'score' => 5.0,
                'time' => 10.0,
            ]);
        }

        // Exécuter le job
        $job = new ProcessRoundElo($round);
        $job->handle(app(\App\Services\EloService::class));

        // Vérifier que les standings ont été créés
        $standings = RoundStanding::where('round_id', $round->id)->get();
        $this->assertCount(3, $standings);

        // Vérifier que is_elo_counted = false
        foreach ($standings as $standing) {
            $this->assertFalse($standing->is_elo_counted);
        }

        // Vérifier que les ELO n'ont PAS été mis à jour
        foreach ($users as $user) {
            $user->refresh();
            $this->assertEquals(1500, $user->elo, "ELO should not have changed for user {$user->id}");
        }

        // Vérifier que les scores ont été nettoyés quand même
        $this->assertEquals(0, Score::where('round_id', $round->id)->count());
    }

    /**
     * Test que les standings sont créés mais is_elo_counted = false avec moins de 3 joueurs
     */
    public function test_less_than_three_players_creates_standings_without_elo_update(): void
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

        $users = User::factory()->count(2)->create(['elo' => 1500]);
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
        ]);

        $playlist = \App\Models\Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
            'provider' => 'youtube',
            'provider_id' => 'test789',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        // Créer des scores
        foreach ($users as $user) {
            Score::create([
                'user_id' => $user->id,
                'round_id' => $round->id,
                'track_id' => $track->id,
                'answer_id' => $trackAnswer->id,
                'score' => 5.0,
                'time' => 10.0,
            ]);
        }

        // Exécuter le job
        $job = new ProcessRoundElo($round);
        $job->handle(app(\App\Services\EloService::class));

        // Vérifier que les standings ont été créés
        $standings = RoundStanding::where('round_id', $round->id)->get();
        $this->assertCount(2, $standings);

        // Vérifier que is_elo_counted = false
        foreach ($standings as $standing) {
            $this->assertFalse($standing->is_elo_counted);
        }

        // Vérifier que les ELO n'ont PAS été mis à jour
        foreach ($users as $user) {
            $user->refresh();
            $this->assertEquals(1500, $user->elo);
        }
    }

    /**
     * Test que les métriques de performance sont calculées correctement
     */
    public function test_performance_metrics_are_calculated_correctly(): void
    {
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
            'track_duration' => 30, // 18% = 5.4 secondes
            'tracks_by_round' => 10,
        ]);

        $user = User::factory()->create(['elo' => 1500]);
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
        ]);

        $playlist = \App\Models\Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
            'provider' => 'youtube',
            'provider_id' => 'test101',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        // Créer des scores avec différents temps
        // 3 réponses rapides (< 5.4s) et 2 réponses normales
        Score::create(['user_id' => $user->id, 'round_id' => $round->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 1.0, 'time' => 3.0]);
        Score::create(['user_id' => $user->id, 'round_id' => $round->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 1.0, 'time' => 4.0]);
        Score::create(['user_id' => $user->id, 'round_id' => $round->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 1.0, 'time' => 5.0]);
        Score::create(['user_id' => $user->id, 'round_id' => $round->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 1.0, 'time' => 10.0]);
        Score::create(['user_id' => $user->id, 'round_id' => $round->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 1.0, 'time' => 15.0]);

        // Ajouter 2 autres joueurs pour que is_elo_counted = true
        $otherUsers = User::factory()->count(2)->create(['elo' => 1500]);
        foreach ($otherUsers as $otherUser) {
            Score::create(['user_id' => $otherUser->id, 'round_id' => $round->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 0.5, 'time' => 20.0]);
        }

        // Exécuter le job
        $job = new ProcessRoundElo($round);
        $job->handle(app(\App\Services\EloService::class));

        // Vérifier les métriques pour le premier joueur
        $standing = RoundStanding::where('round_id', $round->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($standing);
        $this->assertEquals(5, $standing->total_answers_count);
        $this->assertEquals(3, $standing->fast_answers_count); // 3 réponses < 5.4s
        // Temps moyen: (3 + 4 + 5 + 10 + 15) / 5 = 7.4
        $this->assertEquals(7.4, (float) $standing->average_response_time, '', 0.1);
    }

    /**
     * Test que le win streak est calculé correctement
     */
    public function test_win_streak_is_calculated_correctly(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner5@test.com',
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

        $user = User::factory()->create(['elo' => 1500]);
        $otherUsers = User::factory()->count(2)->create(['elo' => 1500]);

        $playlist = \App\Models\Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
            'provider' => 'youtube',
            'provider_id' => 'test202',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        // Créer 3 rounds précédents où l'utilisateur a gagné
        $previousRounds = [];
        for ($i = 1; $i <= 3; $i++) {
            $prevRound = Round::create([
                'room_id' => $room->id,
                'finished_at' => now()->subMinutes($i * 10),
                'is_playing' => false,
                'current' => 0,
            ]);

            // L'utilisateur gagne (score le plus élevé)
            Score::create(['user_id' => $user->id, 'round_id' => $prevRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 10.0]);
            foreach ($otherUsers as $otherUser) {
                Score::create(['user_id' => $otherUser->id, 'round_id' => $prevRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 5.0]);
            }

            // Traiter les rounds précédents
            $prevJob = new ProcessRoundElo($prevRound);
            $prevJob->handle(app(\App\Services\EloService::class));

            $previousRounds[] = $prevRound;
        }

        // Créer le round actuel où l'utilisateur gagne encore
        $currentRound = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
        ]);

        Score::create(['user_id' => $user->id, 'round_id' => $currentRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 10.0]);
        foreach ($otherUsers as $otherUser) {
            Score::create(['user_id' => $otherUser->id, 'round_id' => $currentRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 5.0]);
        }

        // Exécuter le job pour le round actuel
        $job = new ProcessRoundElo($currentRound);
        $job->handle(app(\App\Services\EloService::class));

        // Vérifier le win streak
        $standing = RoundStanding::where('round_id', $currentRound->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($standing);
        $this->assertEquals(1, $standing->position);
        $this->assertEquals(4, $standing->win_streak); // 3 précédents + 1 actuel
    }

    /**
     * Test que le win streak est réinitialisé si le joueur ne gagne pas
     */
    public function test_win_streak_resets_when_player_does_not_win(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner6@test.com',
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

        $user = User::factory()->create(['elo' => 1500]);
        $otherUser = User::factory()->create(['elo' => 1500]);

        $playlist = \App\Models\Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
            'provider' => 'youtube',
            'provider_id' => 'test303',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        // Round où l'utilisateur gagne
        $winRound = Round::create([
            'room_id' => $room->id,
            'finished_at' => now()->subMinutes(10),
            'is_playing' => false,
            'current' => 0,
        ]);

        Score::create(['user_id' => $user->id, 'round_id' => $winRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 10.0]);
        Score::create(['user_id' => $otherUser->id, 'round_id' => $winRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 5.0]);

        $winJob = new ProcessRoundElo($winRound);
        $winJob->handle(app(\App\Services\EloService::class));

        // Round actuel où l'utilisateur perd
        $loseRound = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
        ]);

        Score::create(['user_id' => $user->id, 'round_id' => $loseRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 5.0]);
        Score::create(['user_id' => $otherUser->id, 'round_id' => $loseRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 10.0]);

        // Ajouter un 3ème joueur pour que is_elo_counted = true
        $thirdUser = User::factory()->create(['elo' => 1500]);
        Score::create(['user_id' => $thirdUser->id, 'round_id' => $loseRound->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 3.0]);

        $loseJob = new ProcessRoundElo($loseRound);
        $loseJob->handle(app(\App\Services\EloService::class));

        // Vérifier que le win streak est 0 pour le perdant
        $standing = RoundStanding::where('round_id', $loseRound->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($standing);
        $this->assertNotEquals(1, $standing->position);
        $this->assertEquals(0, $standing->win_streak);
    }

    /**
     * Test que les standings ne sont pas créés en double (race condition)
     */
    public function test_standings_are_not_created_twice(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner7@test.com',
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
        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
        ]);

        $playlist = \App\Models\Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
            'provider' => 'youtube',
            'provider_id' => 'test404',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        foreach ($users as $user) {
            Score::create(['user_id' => $user->id, 'round_id' => $round->id, 'track_id' => $track->id, 'answer_id' => $trackAnswer->id, 'score' => 5.0]);
        }

        // Exécuter le job deux fois
        $job = new ProcessRoundElo($round);
        $job->handle(app(\App\Services\EloService::class));

        $countAfterFirst = RoundStanding::where('round_id', $round->id)->count();

        // Exécuter à nouveau (devrait être ignoré)
        $job->handle(app(\App\Services\EloService::class));

        $countAfterSecond = RoundStanding::where('round_id', $round->id)->count();

        // Le nombre devrait être le même
        $this->assertEquals($countAfterFirst, $countAfterSecond);
        $this->assertEquals(3, $countAfterSecond);
    }

    /**
     * Test que les totaux de score sont corrects après nettoyage
     */
    public function test_total_scores_are_preserved_after_cleanup(): void
    {
        $category = Category::create(['name' => 'Test Category']);
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner8@test.com',
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

        $user = User::factory()->create(['elo' => 1500]);
        $otherUsers = User::factory()->count(2)->create(['elo' => 1500]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => now(),
            'is_playing' => false,
            'current' => 0,
        ]);

        $playlist = \App\Models\Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'test505',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);
        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 1.0,
        ]);

        // Créer plusieurs scores pour le premier joueur
        $expectedTotal = 0;
        for ($i = 0; $i < 5; $i++) {
            $score = 2.0 + $i;
            $expectedTotal += $score;
            Score::create([
                'user_id' => $user->id,
                'round_id' => $round->id,
                'track_id' => $track->id,
                'answer_id' => $trackAnswer->id,
                'score' => $score,
                'time' => 10.0,
            ]);
        }

        // Créer des scores pour les autres joueurs
        foreach ($otherUsers as $otherUser) {
            Score::create([
                'user_id' => $otherUser->id,
                'round_id' => $round->id,
                'track_id' => $track->id,
                'answer_id' => $trackAnswer->id,
                'score' => 5.0,
                'time' => 10.0,
            ]);
        }

        // Vérifier le total avant
        $totalBefore = Score::where('round_id', $round->id)
            ->where('user_id', $user->id)
            ->sum('score');
        $this->assertEquals($expectedTotal, $totalBefore);

        // Exécuter le job
        $job = new ProcessRoundElo($round);
        $job->handle(app(\App\Services\EloService::class));

        // Vérifier que les scores sont supprimés
        $this->assertEquals(0, Score::where('round_id', $round->id)->count());

        // Vérifier que le total est préservé dans les standings
        $standing = RoundStanding::where('round_id', $round->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($standing);
        $this->assertEquals($expectedTotal, (float) $standing->total_score);
    }
}
