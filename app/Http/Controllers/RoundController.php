<?php

namespace App\Http\Controllers;

use App\Events\NewScore;
use App\Events\TrackEnded;
use App\Events\UserHasFoundAllTheAnswers;
use App\Jobs\ProcessAddScoreToTotalScore;
use App\Models\Round;
use App\Models\Score;
use App\Models\Track;
use Illuminate\Http\Request;
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
            $almostAnswers = false;
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
            $newWords = array_filter($newWords, fn($w) => !empty(trim($w)));
            $clientWords = $request->input('words', []);
            // Sanitiser et filtrer les mots du client pour éviter les injections
            $clientWords = is_array($clientWords) ? array_filter(array_map('trim', $clientWords), fn($w) => !empty($w)) : [];
            $userWords = array_unique(array_merge($newWords, $clientWords));

            $alreadyFoundAnswersIds = $user->scores()->where('round_id', $round->id)->where('track_id', $track->id)->pluck('answer_id');

            $trackAnswers = Cache::rememberForever('track-'.$track->id.'-answers', function () use ($track) {
                return $track->answers;
            });

            $remainingAnswers = $trackAnswers->whereNotIn('id', $alreadyFoundAnswersIds)->all();

            foreach ($remainingAnswers as $answer) {
                $value = sanitizeString($answer->value);
                $answerWords = array_unique(explode(' ', $value));
                $goodWords = [];
                $score = 0;

                // Optimisation : vérification exacte rapide en premier (cas le plus courant)
                if ($sanitized === $value) {
                    $goodWords = $answerWords;
                } else {
                    // Vérification mot par mot (plus performant que Levenshtein global)
                    foreach ($answerWords as $word) {
                        $wordFound = false;
                        
                        foreach ($userWords as $userWord) {
                            // Cas 1 : Comparaison exacte (le plus rapide, cas le plus courant)
                            if ($userWord === $word) {
                                $goodWords[] = $word;
                                $wordFound = true;
                                break;
                            }
                            
                            // Cas 2 : Nombres - comparaison stricte uniquement (pas de tolérance)
                            if (is_numeric($userWord) && is_numeric($word)) {
                                $userWordNormalized = (string) $userWord;
                                $wordNormalized = (string) $word;
                                if ($userWordNormalized === $wordNormalized) {
                                    $goodWords[] = $word;
                                    $wordFound = true;
                                }
                                // Ne pas continuer avec Levenshtein pour les nombres
                                break;
                            }
                            
                            // Cas 3 : Mots courts de la réponse (< 5 caractères) - comparaison stricte uniquement
                            // Si le mot de la réponse est court, on ne doit jamais utiliser Levenshtein
                            if (strlen($word) < 5 && !is_numeric($word)) {
                                // Pour les mots courts de la réponse, on ne fait que la comparaison exacte (déjà fait ci-dessus)
                                // Si on arrive ici, le mot ne correspond pas strictement
                                continue;
                            }
                            
                            // Cas 4 : Mots longs (>= 5 caractères) - Levenshtein (le plus coûteux, en dernier)
                            // On applique Levenshtein seulement si les deux mots sont longs
                            if (strlen($userWord) >= 5 && strlen($word) >= 5 && !is_numeric($userWord) && !is_numeric($word)) {
                                if (levenshtein($userWord, $word) < 1.55) {
                                    $goodWords[] = $word;
                                    $wordFound = true;
                                    break;
                                }
                            }
                        }
                    }
                    
                    // Si aucun mot ne correspond, vérifier Levenshtein global (dernier recours, coûteux)
                    if (empty($goodWords) && levenshtein($sanitized, $value) < 3) {
                        // Mais avant d'accepter, vérifier que les nombres et mots courts correspondent strictement
                        $canAcceptLevenshtein = true;
                        
                        foreach ($answerWords as $word) {
                            // Vérifier les nombres
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
                                if (!$wordFound) {
                                    $canAcceptLevenshtein = false;
                                    break;
                                }
                            }
                            
                            // Vérifier les mots courts
                            if (strlen($word) < 5 && !is_numeric($word)) {
                                $wordFound = false;
                                foreach ($userWords as $userWord) {
                                    if (strlen($userWord) < 5 && !is_numeric($userWord) && $userWord === $word) {
                                        $wordFound = true;
                                        break;
                                    }
                                }
                                if (!$wordFound) {
                                    $canAcceptLevenshtein = false;
                                    break;
                                }
                            }
                        }
                        
                        if ($canAcceptLevenshtein) {
                            $goodWords = $answerWords;
                        }
                    }
                }

                $goodWords = array_unique($goodWords);

                // All good
                if (count($answerWords) === count($goodWords)) {
                    $score = $answer->score;
                    $goodAnswers[] = $answer;

                    // Only apply bonuses if the base score is greater than 0
                    if ($answer->score > 0) {
                        // Bonuses - Optimize: use count query with conditions instead of fetching all
                        $order = Score::where('round_id', $round->id)
                            ->where('track_id', $track->id)
                            ->where('answer_id', $answer->id)
                            ->count() + 1;
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
                        $order = Score::where('round_id', $round->id)
                            ->where('track_id', $track->id)
                            ->where('answer_id', $answer->id)
                            ->count() + 1;
                        $actualSpeedBonus = false;
                    }

                    $answers[] = [
                        'id' => $answer->id,
                        'order' => $order,
                        'speedBonus' => $actualSpeedBonus,
                        'name' => $answer->type->name,
                        'value' => $answer->value, // Include original value with parentheses for display
                    ];

                    // Save score to db - use insert for faster performance
                    $savedScore = $user->scores()->create([
                        'team_id' => $user?->team?->id,
                        'round_id' => $round->id,
                        'track_id' => $track->id,
                        'answer_id' => $answer->id,
                        'score' => $score,
                        'time' => $request->input('currentTime'),
                    ]);

                    // Increment total scores - dispatch async to not block response
                    // This will also update the user level if the score is from a public room
                    ProcessAddScoreToTotalScore::dispatch($savedScore);

                } elseif (count($goodWords) >= (count($answerWords) / 2)) {
                    $almostAnswers = true;
                }
            }

            if (! empty($goodAnswers)) {
                $totalUserAnswers = $user->scores()->where('round_id', $round->id)->where('track_id', $track->id)->count();
                $totalTrackAnswers = $trackAnswers->count();
                $message = $this->getMessage('good');

                // Broadcast score to everyone
                broadcast(new NewScore([
                    'room_id' => $round->room->id,
                    'user_id' => $user->id,
                    'track_id' => $track->id,
                    'answers' => $answers,
                    'total' => $round->userScore($user),
                    'time' => $request->input('currentTime'),
                ]));

                // If user has found all the answers send the bubble to the player
                if ($totalUserAnswers === $totalTrackAnswers) {
                    broadcast(new UserHasFoundAllTheAnswers($round->room, [
                        'name' => $user->name,
                        'id' => $user->id,
                        'photo' => $user->photo,
                        'time' => $request->input('currentTime'),
                    ]));
                }
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
    }
}
