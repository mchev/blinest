<?php

namespace App\Http\Controllers;

use App\Events\NewScore;
use App\Events\TrackEnded;
use App\Events\UserHasFoundAllTheAnswers;
use App\Models\Round;
use App\Models\Score;
use App\Models\Track;
use App\Models\TrackAnswer;
use App\Services\RoundScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RoundController extends Controller
{
    public function pause(Round $round)
    {
        (Auth::user()->hasRoomControl($round->room))
            ? $round->pause()
            : abort(403, __('Unauthorized action'));
    }

    public function resume(Round $round)
    {
        (Auth::user()->hasRoomControl($round->room))
            ? $round->resume()
            : abort(403, __('Unauthorized action'));
    }

    public function stop(Round $round)
    {
        if (Auth::user()->hasRoomControl($round->room)) {
            $round->stop();
            broadcast(new TrackEnded($round));
        } else {
            abort(403, __('Unauthorized action'));
        }
    }

    public function getMessage(string $type): array
    {
        return [
            'type' => $type,
            'body' => __('messages.'.$type)[array_rand(__('messages.'.$type))],
        ];
    }

    public function check(Request $request, Round $round, Track $track)
    {
        // Check if the round is still running and if the track is corresponding
        if (! $round->finished_at && $round->tracks[$round->current - 1] === $track->id) {

            // Validate
            $request->validate([
                'text' => 'required|string|min:1|max:255',
                'words' => 'nullable|array',
                'currentTime' => 'required|numeric|min:0',
            ]);

            $user = $request->user();
            $goodAnswers = [];
            $answers = [];
            $almostAnswers = false;
            $duplicateAnswers = []; // Track duplicate answers to inform user
            $trackDuration = Cache::rememberForever('track_'.$track->id.'_duration', function () use ($round) {
                return $round->room->track_duration;
            });

            // Security: Validate currentTime is within reasonable bounds
            // Allow some tolerance for network/processing delays (max 5 seconds over track duration)
            $currentTime = (float) $request->input('currentTime');
            $maxAllowedTime = $trackDuration + 5;

            if ($currentTime < 0 || $currentTime > $maxAllowedTime) {
                // Invalid time, reject the request
                return response()->json([
                    'error' => 'Invalid time',
                ], 400);
            }

            $speedBonus = ($currentTime < ($trackDuration * 0.18));

            // Updates the words array
            $sanitized = sanitizeString($request->input('text'));

            // Check for hint command (!indice or !hint)
            $textLower = strtolower(trim($request->input('text')));
            if ($textLower === '!indice' || $textLower === '!hint') {
                if ($track->hint) {
                    return response()->json([
                        'message' => [
                            'type' => 'hint',
                            'body' => $track->hint,
                        ],
                        'words' => $request->input('words', []),
                        'good_answers' => [],
                    ]);
                } else {
                    return response()->json([
                        'message' => [
                            'type' => 'bad',
                            'body' => __('No hint available'),
                        ],
                        'words' => $request->input('words', []),
                        'good_answers' => [],
                    ]);
                }
            }

            $newWords = explode(' ', $sanitized);
            // Filtrer les chaînes vides et sanitiser les mots du client
            $newWords = array_filter($newWords, fn ($w) => ! empty(trim($w)));
            $clientWords = $request->input('words', []);
            // Sanitiser et filtrer les mots du client pour éviter les injections
            $clientWords = is_array($clientWords) ? array_filter(array_map('trim', $clientWords), fn ($w) => ! empty($w)) : [];
            $userWords = array_unique(array_merge($newWords, $clientWords));

            // Récupérer les answer_ids déjà trouvés depuis Redis (nouveau système)
            // Fallback vers scores DB pour compatibilité avec anciens rounds
            $roundScoreService = app(RoundScoreService::class);
            $alreadyFoundAnswersIds = $roundScoreService->getFoundAnswerIds($round->id, $user->id, $track->id);

            // Si Redis est vide, fallback vers scores DB (anciens rounds)
            if (empty($alreadyFoundAnswersIds)) {
                $alreadyFoundAnswersIds = $user->scores()
                    ->where('round_id', $round->id)
                    ->where('track_id', $track->id)
                    ->pluck('answer_id')
                    ->toArray();
            }

            $trackAnswers = $this->getCachedTrackAnswers($track);

            $remainingAnswers = $trackAnswers->whereNotIn('id', $alreadyFoundAnswersIds)->all();

            foreach ($remainingAnswers as $answer) {
                $candidateLines = $this->candidateLinesForTrackAnswer($answer);
                $lineComplete = false;
                $lineAlmost = false;

                foreach ($candidateLines as $rawLine) {
                    $match = $this->evaluateMatchForAnswerLine($sanitized, $userWords, $rawLine);
                    if ($match['complete']) {
                        $lineComplete = true;

                        break;
                    }
                    if ($match['almost']) {
                        $lineAlmost = true;
                    }
                }

                $score = 0;

                if ($lineComplete) {
                    $score = $answer->score;
                    $goodAnswers[] = $answer;

                    // Calculer l'ordre (premier, deuxième, troisième) AVANT d'ajouter la réponse
                    // On compte combien de joueurs ont déjà trouvé cette réponse (sans nous)
                    $roundScoreService = app(RoundScoreService::class);
                    $playersWhoFoundAnswer = $roundScoreService->countPlayersWhoFoundAnswer($round->id, $track->id, $answer->id, $user->id);
                    $order = $playersWhoFoundAnswer + 1; // +1 car on est le prochain

                    // Only apply bonuses if the base score is greater than 0
                    if ($answer->score > 0) {
                        if ($order < 4) {
                            $score += 0.5;
                        }

                        // Flamme - Bonus speed (18% of the room track duration)
                        // Only apply speed bonus if the base score is not 0
                        $actualSpeedBonus = false;
                        if ($speedBonus) {
                            $score += 0.5;
                            $actualSpeedBonus = true;
                        }
                    } else {
                        // No bonuses for 0-point answers
                        $actualSpeedBonus = false;
                    }

                    // Vérifier atomiquement si cette réponse a déjà été trouvée
                    // recordTrackDetails retourne false si la réponse existe déjà
                    $wasNewAnswer = $roundScoreService->recordTrackDetails(
                        $round->id,
                        $user->id,
                        $track->id,
                        $request->input('currentTime'),
                        null, // position sera calculée à la fin du round
                        $score,
                        $answer->id // answer_id pour recoupements
                    );

                    // Si la réponse existait déjà, l'ajouter aux doublons et continuer
                    if (! $wasNewAnswer) {
                        $duplicateAnswers[] = $answer;

                        continue;
                    }

                    $answers[] = [
                        'id' => $answer->id,
                        'order' => $order,
                        'speedBonus' => $actualSpeedBonus,
                        'name' => $answer->type->name,
                        'value' => $answer->value, // Include original value with parentheses for display
                    ];

                    // Utiliser Redis pour les scores en temps réel (plus performant)
                    // On ne crée plus de Score en DB pendant le round
                    $roundScoreService->addScore($round->id, $user->id, $score);

                    // Pour la compatibilité et les métriques, on peut encore créer un Score
                    // mais seulement si nécessaire (pour les métriques de performance)
                    // Pour l'instant, on stocke juste le total dans Redis
                    // Les métriques détaillées seront calculées depuis Redis si nécessaire

                } elseif ($lineAlmost) {
                    $almostAnswers = true;
                }
            }

            if (! empty($goodAnswers)) {
                // Récupérer le score total depuis Redis
                $roundScoreService = app(RoundScoreService::class);
                $totalScore = $roundScoreService->getUserScore($round->id, $user->id);

                // Compter toutes les réponses trouvées par l'utilisateur pour cette track (depuis Redis)
                $totalUserAnswers = $roundScoreService->countFoundAnswersForUser($round->id, $user->id, $track->id);
                $totalTrackAnswers = $trackAnswers->count();
                $message = $this->getMessage('good');

                // Broadcast score to everyone (only if we have new answers)
                if (! empty($answers)) {
                    broadcast(new NewScore([
                        'room_id' => $round->room->id,
                        'user_id' => $user->id,
                        'track_id' => $track->id,
                        'answers' => $answers,
                        'total' => $totalScore,
                        'time' => $request->input('currentTime'),
                    ]));
                }

                // If user has found all the answers send the bubble to the player
                if ($totalUserAnswers === $totalTrackAnswers) {
                    broadcast(new UserHasFoundAllTheAnswers($round->room, [
                        'name' => $user->name,
                        'id' => $user->id,
                        'photo' => $user->photo,
                        'time' => $request->input('currentTime'),
                    ]));
                }
            } elseif (! empty($duplicateAnswers)) {
                // Si toutes les réponses sont des doublons, informer l'utilisateur
                // mais ne pas retourner un message "bad" car les réponses étaient correctes
                $message = [
                    'type' => 'good',
                    'body' => __('You have already found these answers'),
                ];
            } elseif ($almostAnswers) {
                $message = $this->getMessage('almost');
            } else {
                $message = $this->getMessage('bad');
            }

            return response()->json([
                'words' => $userWords,
                'good_answers' => $goodAnswers,
                'message' => $message,
            ], 200);
        }

        // Round ended or track mismatch: explicit response so the client can handle it
        $error = $round->finished_at ? 'round_ended' : 'track_mismatch';

        return response()->json(['error' => $error], 409);
    }

    /**
     * Enregistre qu'un joueur a écouté une track (même sans trouver de réponse)
     * Utilise Redis pour éviter les écritures DB pendant le round
     */
    public function trackListened(Request $request, Round $round, Track $track)
    {
        $user = $request->user();

        // Vérifier que la track fait partie du round
        $tracks = (array) $round->tracks;
        if (! in_array($track->id, $tracks)) {
            return response()->json(['error' => 'Track not in round'], 400);
        }

        // Vérifier que le round est en cours
        if ($round->finished_at) {
            return response()->json(['error' => 'Round is finished'], 400);
        }

        // Enregistrer dans Redis (plus performant que DB)
        // On enregistre juste que la track a été écoutée (sans score si pas de réponse trouvée)
        $roundScoreService = app(RoundScoreService::class);
        $roundScoreService->recordTrackDetails($round->id, $user->id, $track->id);

        return response()->json(['success' => true], 200);
    }

    /**
     * Récupère tous les scores d'un round depuis Redis
     */
    public function scores(Round $round)
    {
        $roundScoreService = app(RoundScoreService::class);
        $scores = $roundScoreService->getAllScores($round->id);

        return response()->json(['scores' => $scores], 200);
    }

    private function getCachedTrackAnswers(Track $track): Collection
    {
        $key = 'track-'.$track->id.'-answers';

        return Cache::rememberForever($key, fn () => $track->answers);
    }

    /**
     * @return list<string>
     */
    private function candidateLinesForTrackAnswer(TrackAnswer $answer): array
    {
        $lines = [trim((string) $answer->value)];
        $aliases = is_array($answer->aliases) ? $answer->aliases : [];

        foreach ($aliases as $alias) {
            if (! is_string($alias)) {
                continue;
            }
            $trimmed = trim($alias);
            if ($trimmed !== '') {
                $lines[] = $trimmed;
            }
        }

        $seen = [];
        $unique = [];

        foreach ($lines as $line) {
            $key = mb_strtolower($line);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $unique[] = $line;
        }

        return $unique;
    }

    /**
     * @return array{complete: bool, almost: bool}
     */
    private function evaluateMatchForAnswerLine(string $sanitized, array $userWords, string $rawLine): array
    {
        $value = sanitizeString(trim($rawLine));
        if ($value === '') {
            return ['complete' => false, 'almost' => false];
        }

        $answerWords = array_values(array_unique(array_filter(explode(' ', $value), fn ($w) => $w !== '')));

        if ($answerWords === []) {
            return ['complete' => false, 'almost' => false];
        }

        $goodWords = [];

        if ($sanitized === $value) {
            $goodWords = $answerWords;
        } elseif (levenshtein($sanitized, $value) < 3) {
            $canAcceptLevenshtein = true;

            foreach ($answerWords as $word) {
                if (is_numeric($word)) {
                    $wordFound = false;
                    foreach ($userWords as $userWord) {
                        if (is_numeric($userWord)) {
                            $userWordNormalized = (string) $userWord;
                            $wordNormalized = (string) $word;
                            if ($userWordNormalized === $wordNormalized) {
                                $wordFound = true;

                                break;
                            }
                        }
                    }
                    if (! $wordFound) {
                        $canAcceptLevenshtein = false;

                        break;
                    }
                }
            }

            if ($canAcceptLevenshtein) {
                $goodWords = $answerWords;
            } else {
                foreach ($answerWords as $word) {
                    foreach ($userWords as $userWord) {
                        if (is_numeric($userWord) && is_numeric($word)) {
                            $userWordNormalized = (string) $userWord;
                            $wordNormalized = (string) $word;
                            if ($userWordNormalized === $wordNormalized) {
                                $goodWords[] = $word;
                            }
                        } elseif (strlen($userWord) < 5) {
                            if ($userWord === $word) {
                                $goodWords[] = $word;
                            }
                        } elseif (levenshtein($userWord, $word) < 1.55) {
                            $goodWords[] = $word;
                        }
                    }
                }
            }
        } else {
            foreach ($answerWords as $word) {
                foreach ($userWords as $userWord) {
                    if (is_numeric($userWord) && is_numeric($word)) {
                        $userWordNormalized = (string) $userWord;
                        $wordNormalized = (string) $word;
                        if ($userWordNormalized === $wordNormalized) {
                            $goodWords[] = $word;
                        }
                    } elseif (strlen($userWord) < 5) {
                        if ($userWord === $word) {
                            $goodWords[] = $word;
                        }
                    } elseif (levenshtein($userWord, $word) < 1.55) {
                        $goodWords[] = $word;
                    }
                }
            }
        }

        $goodWords = array_unique($goodWords);
        $complete = count($answerWords) === count($goodWords);
        $almost = ! $complete && count($goodWords) >= (count($answerWords) / 2);

        return ['complete' => $complete, 'almost' => $almost];
    }
}
