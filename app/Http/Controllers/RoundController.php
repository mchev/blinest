<?php

namespace App\Http\Controllers;

use App\Events\NewScore;
use App\Events\TrackEnded;
use App\Events\UserHasFoundAllTheAnswers;
use App\Jobs\ProcessAddScoreToTotalScore;
use App\Models\Round;
use App\Models\Score;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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

    public function check(Round $round, Track $track)
    {
        // Check if the round is still running and if the track is corresponding
        if (! $round->finished_at && $round->tracks[$round->current - 1] === $track->id) {

            // Validate
            Request::validate([
                'text' => 'required|string|min:1',
                'words' => 'nullable|array',
                'currentTime' => 'required|numeric',
            ]);

            $user = Auth::user();
            $goodAnswers = [];
            $almostAnswers = false;
            $speedBonus = (Request::input('currentTime') < ($round->room->track_duration * 0.18));

            // Updates the words array
            $sanitized = sanitizeString(Request::input('text'));
            $newWords = explode(' ', $sanitized);
            $userWords = array_unique(array_merge($newWords, Request::input('words')));

            $alreadyFoundAnswersIds = $user->scores()->where('round_id', $round->id)->where('track_id', $track->id)->pluck('answer_id');
            $remainingAnswers = $track->answers()->whereNotIn('id', $alreadyFoundAnswersIds)->get();

            foreach ($remainingAnswers as $answer) {
                $value = sanitizeString($answer->value);
                $answerWords = array_unique(explode(' ', $value));
                $goodWords = [];
                $score = 0;

                // Checking all sentence
                if (levenshtein($sanitized, $value) < 3) {
                    $goodWords = $answerWords;
                } else {
                    // Else Checking all words
                    foreach ($answerWords as $word) {
                        foreach ($userWords as $userWord) {
                            if (levenshtein($userWord, $word) < 1.55) {
                                $goodWords[] = $word;
                            }
                        }
                    }
                }

                $goodWords = array_unique($goodWords);

                // All good
                if (count($answerWords) === count($goodWords)) {
                    $score = $answer->score;
                    $goodAnswers[] = $answer;

                    // Bonuses
                    $order = Score::where('round_id', $round->id)
                        ->where('track_id', $track->id)
                        ->where('answer_id', $answer->id)
                        ->count() + 1;
                    if ($order < 4) {
                        $score += 0.5;
                    }

                    // Flamme - Bonus speed (18% of the room track duration)
                    if ($speedBonus) {
                        $score += 0.5;
                    }

                    $answers[] = [
                        'id' => $answer->id,
                        'order' => $order,
                        'speedBonus' => $speedBonus,
                        'name' => $answer->type->name,
                    ];

                    // Save score to db
                    $savedScore = $user->scores()->create([
                        'team_id' => $user?->team?->id,
                        'round_id' => $round->id,
                        'track_id' => $track->id,
                        'answer_id' => $answer->id,
                        'score' => $score,
                        'time' => Request::input('currentTime'),
                    ]);

                    // Increment total scores
                    ProcessAddScoreToTotalScore::dispatch($savedScore);
                } elseif (count($goodWords) >= (count($answerWords) / 2)) {
                    $almostAnswers = true;
                }
            }

            if (! empty($goodAnswers)) {
                $totalUserAnswers = $user->scores()->where('round_id', $round->id)->where('track_id', $track->id)->count();
                $totalTrackAnswers = $track->answers()->count();
                $message = $this->getMessage('good');

                // Broadcast score to everyone
                broadcast(new NewScore([
                    'room_id' => $round->room->id,
                    'user_id' => $user->id,
                    'track_id' => $track->id,
                    'answers' => $answers,
                    'total' => $round->userScore($user),
                    'time' => Request::input('currentTime'),
                ]));

                // Broadcast all answers player bubble
                if ($totalUserAnswers === $totalTrackAnswers) {
                    broadcast(new UserHasFoundAllTheAnswers($round->room, [
                        'name' => $user->name,
                        'id' => $user->id,
                        'photo' => $user->photo,
                        'time' => Request::input('currentTime'),
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
