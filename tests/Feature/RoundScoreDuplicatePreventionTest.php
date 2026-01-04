<?php

namespace Tests\Feature;

use App\Models\AnswerType;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\Room;
use App\Models\Round;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Models\User;
use App\Services\RoundScoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RoundScoreDuplicatePreventionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Vérifier si Redis est disponible
        try {
            Redis::ping();
            // Nettoyer Redis avant chaque test
            Redis::flushall();
        } catch (\Exception $e) {
            $this->markTestSkipped('Redis is not available: '.$e->getMessage());
        }
    }

    protected function tearDown(): void
    {
        // Nettoyer Redis après chaque test
        try {
            Redis::flushall();
        } catch (\Exception $e) {
            // Ignorer si Redis n'est pas disponible
        }
        parent::tearDown();
    }

    /**
     * Test qu'on ne peut pas ajouter le même score deux fois pour la même réponse
     */
    public function test_cannot_add_same_answer_twice(): void
    {
        $roundScoreService = app(RoundScoreService::class);
        $roundId = 1;
        $userId = 1;
        $trackId = 1;
        $answerId = 1;
        $score = 5.0;

        // Première fois : doit réussir
        $result1 = $roundScoreService->recordTrackDetails(
            $roundId,
            $userId,
            $trackId,
            10.0,
            null,
            $score,
            $answerId
        );
        $this->assertTrue($result1, 'First record should succeed');

        // Ajouter le score (recordTrackDetails ne l'ajoute pas automatiquement)
        $roundScoreService->addScore($roundId, $userId, $score);

        // Vérifier que le score a été ajouté
        $totalScore = $roundScoreService->getUserScore($roundId, $userId);
        $this->assertEquals($score, $totalScore);

        // Deuxième fois avec la même réponse : doit échouer
        $result2 = $roundScoreService->recordTrackDetails(
            $roundId,
            $userId,
            $trackId,
            15.0,
            null,
            $score,
            $answerId
        );
        $this->assertFalse($result2, 'Second record with same answer should fail');

        // Vérifier que le score n'a PAS été ajouté une deuxième fois
        $totalScoreAfter = $roundScoreService->getUserScore($roundId, $userId);
        $this->assertEquals($score, $totalScoreAfter, 'Score should not be added twice');
    }

    /**
     * Test que getFoundAnswerIds retourne bien les réponses déjà trouvées
     */
    public function test_get_found_answer_ids_returns_correct_answers(): void
    {
        $roundScoreService = app(RoundScoreService::class);
        $roundId = 1;
        $userId = 1;
        $trackId = 1;

        // Aucune réponse trouvée au début
        $foundAnswers = $roundScoreService->getFoundAnswerIds($roundId, $userId, $trackId);
        $this->assertEmpty($foundAnswers);

        // Ajouter une première réponse
        $roundScoreService->recordTrackDetails($roundId, $userId, $trackId, 10.0, null, 5.0, 1);
        $roundScoreService->addScore($roundId, $userId, 5.0);
        $foundAnswers = $roundScoreService->getFoundAnswerIds($roundId, $userId, $trackId);
        $this->assertCount(1, $foundAnswers);
        $this->assertContains(1, $foundAnswers);

        // Ajouter une deuxième réponse différente
        $roundScoreService->recordTrackDetails($roundId, $userId, $trackId, 12.0, null, 3.0, 2);
        $roundScoreService->addScore($roundId, $userId, 3.0);
        $foundAnswers = $roundScoreService->getFoundAnswerIds($roundId, $userId, $trackId);
        $this->assertCount(2, $foundAnswers);
        $this->assertContains(1, $foundAnswers);
        $this->assertContains(2, $foundAnswers);

        // Essayer d'ajouter la première réponse à nouveau (ne doit pas être ajoutée)
        $result = $roundScoreService->recordTrackDetails($roundId, $userId, $trackId, 15.0, null, 5.0, 1);
        $this->assertFalse($result, 'Duplicate answer should not be recorded');
        $foundAnswers = $roundScoreService->getFoundAnswerIds($roundId, $userId, $trackId);
        $this->assertCount(2, $foundAnswers, 'Should still have only 2 answers');
    }

    /**
     * Test que plusieurs réponses différentes pour la même track fonctionnent
     */
    public function test_multiple_different_answers_for_same_track_work(): void
    {
        $roundScoreService = app(RoundScoreService::class);
        $roundId = 1;
        $userId = 1;
        $trackId = 1;

        // Ajouter plusieurs réponses différentes
        $roundScoreService->recordTrackDetails($roundId, $userId, $trackId, 10.0, null, 5.0, 1);
        $roundScoreService->addScore($roundId, $userId, 5.0);
        $roundScoreService->recordTrackDetails($roundId, $userId, $trackId, 12.0, null, 3.0, 2);
        $roundScoreService->addScore($roundId, $userId, 3.0);
        $roundScoreService->recordTrackDetails($roundId, $userId, $trackId, 14.0, null, 2.0, 3);
        $roundScoreService->addScore($roundId, $userId, 2.0);

        $foundAnswers = $roundScoreService->getFoundAnswerIds($roundId, $userId, $trackId);
        $this->assertCount(3, $foundAnswers);
        $this->assertContains(1, $foundAnswers);
        $this->assertContains(2, $foundAnswers);
        $this->assertContains(3, $foundAnswers);

        // Vérifier le score total
        $totalScore = $roundScoreService->getUserScore($roundId, $userId);
        $this->assertEquals(10.0, $totalScore); // 5.0 + 3.0 + 2.0
    }

    /**
     * Test que les réponses pour différentes tracks sont indépendantes
     */
    public function test_answers_for_different_tracks_are_independent(): void
    {
        $roundScoreService = app(RoundScoreService::class);
        $roundId = 1;
        $userId = 1;

        // Même answer_id mais pour des tracks différentes
        $roundScoreService->recordTrackDetails($roundId, $userId, 1, 10.0, null, 5.0, 1);
        $roundScoreService->recordTrackDetails($roundId, $userId, 2, 12.0, null, 3.0, 1); // Même answer_id mais track différente

        $foundAnswersTrack1 = $roundScoreService->getFoundAnswerIds($roundId, $userId, 1);
        $foundAnswersTrack2 = $roundScoreService->getFoundAnswerIds($roundId, $userId, 2);

        $this->assertCount(1, $foundAnswersTrack1);
        $this->assertCount(1, $foundAnswersTrack2);
        $this->assertContains(1, $foundAnswersTrack1);
        $this->assertContains(1, $foundAnswersTrack2);
    }

    /**
     * Test de race condition : simulation de plusieurs requêtes simultanées
     */
    public function test_race_condition_prevention(): void
    {
        $roundScoreService = app(RoundScoreService::class);
        $roundId = 1;
        $userId = 1;
        $trackId = 1;
        $answerId = 1;
        $score = 5.0;

        // Simuler plusieurs tentatives "simultanées" d'ajouter la même réponse
        $results = [];
        for ($i = 0; $i < 10; $i++) {
            $result = $roundScoreService->recordTrackDetails(
                $roundId,
                $userId,
                $trackId,
                10.0 + $i,
                null,
                $score,
                $answerId
            );
            $results[] = $result;
            // Ajouter le score seulement si c'est une nouvelle réponse
            if ($result) {
                $roundScoreService->addScore($roundId, $userId, $score);
            }
        }

        // Seule la première devrait réussir
        $successCount = count(array_filter($results, fn ($r) => $r === true));
        $this->assertEquals(1, $successCount, 'Only first attempt should succeed');

        // Vérifier que le score n'a été ajouté qu'une seule fois
        $totalScore = $roundScoreService->getUserScore($roundId, $userId);
        $this->assertEquals($score, $totalScore, 'Score should be added only once');
    }

    /**
     * Test avec un round réel et un utilisateur réel
     */
    public function test_duplicate_prevention_with_real_round(): void
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

        $user = User::factory()->create(['elo' => 1500]);

        $playlist = Playlist::create([
            'name' => 'Test Playlist',
            'user_id' => $owner->id,
        ]);
        $answerType = AnswerType::create(['name' => 'Artist']);

        $track = Track::create([
            'playlist_id' => $playlist->id,
            'user_id' => $owner->id,
            'provider' => 'youtube',
            'provider_id' => 'test123',
            'preview_url' => 'https://example.com/preview',
            'artwork_url' => 'https://example.com/artwork',
        ]);

        $trackAnswer = TrackAnswer::create([
            'track_id' => $track->id,
            'answer_type_id' => $answerType->id,
            'value' => 'Test Artist',
            'score' => 5.0,
        ]);

        $round = Round::create([
            'room_id' => $room->id,
            'finished_at' => null,
            'is_playing' => true,
            'current' => 1,
            'tracks' => [$track->id],
        ]);

        $roundScoreService = app(RoundScoreService::class);

        // Première soumission de la réponse
        $result1 = $roundScoreService->recordTrackDetails(
            $round->id,
            $user->id,
            $track->id,
            10.0,
            null,
            5.0,
            $trackAnswer->id
        );
        $this->assertTrue($result1);
        $roundScoreService->addScore($round->id, $user->id, 5.0);

        $score1 = $roundScoreService->getUserScore($round->id, $user->id);
        $this->assertEquals(5.0, $score1);

        // Deuxième soumission de la même réponse
        $result2 = $roundScoreService->recordTrackDetails(
            $round->id,
            $user->id,
            $track->id,
            15.0,
            null,
            5.0,
            $trackAnswer->id
        );
        $this->assertFalse($result2, 'Duplicate answer should be rejected');

        // Vérifier que le score n'a pas été ajouté une deuxième fois
        $score2 = $roundScoreService->getUserScore($round->id, $user->id);
        $this->assertEquals(5.0, $score2, 'Score should remain the same');

        // Vérifier que getFoundAnswerIds retourne bien cette réponse
        $foundAnswers = $roundScoreService->getFoundAnswerIds($round->id, $user->id, $track->id);
        $this->assertCount(1, $foundAnswers);
        $this->assertContains($trackAnswer->id, $foundAnswers);
    }
}
