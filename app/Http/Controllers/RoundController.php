<?php

namespace App\Http\Controllers;

use App\Events\NewScore;
use App\Events\TrackEnded;
use App\Events\UserHasFoundAllTheAnswers;
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
            : abort(403, 'Unauthorized action.');
    }

    public function resume(Round $round)
    {
        (Auth::user()->hasRoomControl($round->room))
            ? $round->resume()
            : abort(403, 'Unauthorized action.');
    }

    public function stop(Round $round)
    {
        if (Auth::user()->hasRoomControl($round->room)) {
            $round->stop();
            broadcast(new TrackEnded($round));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function check(Round $round, Track $track)
    {
        if (! $round->finished_at && $round->tracks[$round->current - 1] === $track->id) {
            Request::validate([
                'text' => 'required|string|min:2',
            ]);

            $total = $round->userScore(Auth::user());
            $input = sanitizeString(Request::input('text'));
            $score = 0;
            $answers = [];
            $good_answers = [];
            $almost_answers = [];
            $bad_answers = [];

            foreach ($track->answers as $answer) {
                if (! Auth::user()->scores()->where('round_id', $round->id)->where('answer_id', $answer->id)->exists()) {
                    $value = sanitizeString($answer->value);

                    if (str_contains($input, $value)) {
                        $similarity = 0;
                    } else {
                        $similarity = levenshtein($input, $value);
                    }

                    // Good
                    if ($similarity < 3) {
                        $good_answers[] = $answer;
                        $score += $answer->score;

                        // Score order
                        $order = Score::where('round_id', $round->id)
                            ->where('track_id', $track->id)
                            ->where('answer_id', $answer->id)
                            ->count() + 1;
                        if ($order < 4) {
                            $score += 0.5;
                        }

                        // Bonus speed (10% of the room track duration)
                        $speedBonus = (Request::input('currentTime') < ($round->room->track_duration * 0.15));
                        if ($speedBonus) {
                            $score += 0.5;
                        }

                        $answers[] = [
                            'id' => $answer->id,
                            'order' => $order,
                            'speedBonus' => $speedBonus,
                            'name' => $answer->type->name,
                        ];

                        // Store the score in database
                        Auth::user()->scores()->create([
                            'team_id' => Auth::user()?->team?->id,
                            'round_id' => $round->id,
                            'track_id' => $track->id,
                            'answer_id' => $answer->id,
                            'score' => $score,
                            'time' => Request::input('currentTime'),
                        ]);

                        $totalUserAnswers = Auth::user()->scores()->where('round_id', $round->id)->where('track_id', $track->id)->count();
                        $totalTrackAnswers = $track->answers()->count();

                        if ($totalUserAnswers === $totalTrackAnswers) {
                            broadcast(new UserHasFoundAllTheAnswers($round->room, [
                                'name' => Auth::user()->name,
                                'id' => Auth::user()->id,
                                'photo' => Auth::user()->photo,
                                'time' => Request::input('currentTime'),
                            ]));
                        }
                    }

                    // Almost
                    if ($similarity <= 4) {
                        $almost_answers[] = $answer;
                    }

                    // Nope
                    if ($similarity > 4) {
                        $bad_answers[] = $answer;
                    }
                }
            }

            // Generate message
            if (count($good_answers)) {

                // Broadcast score
                broadcast(new NewScore([
                    'room_id' => $round->room->id,
                    'user_id' => Auth::user()->id,
                    'track_id' => $track->id,
                    'answers' => $answers,
                    'points' => $score,
                    'total' => $total + $score,
                    'time' => Request::input('currentTime'),
                ]));

                $message = [
                    'type' => 'success',
                    'body' => $this->getRandomSuccessMessage(),
                ];
            } elseif (count($almost_answers)) {
                $message = [
                    'type' => 'warning',
                    'body' => 'Presque !',
                ];
            } else {
                $message = [
                    'type' => 'error',
                    'body' => $this->getRandomErrorMessage(),
                ];
            }

            // Return message to user
            return response()->json([
                'good_answers' => $good_answers,
                'message' => $message,
            ], 200);
        }
    }

    public function getRandomSuccessMessage()
    {
        $messages = [
            'Bien joué !',
            'Félicitation !',
            'Je suis vraiment fier de toi !',
            'Je savais que tu pouvais y arriver !',
            'Trop fort !',
            'Chapeau !',
        ];
        $random_index = array_rand($messages);

        return $messages[$random_index];
    }

    public function getRandomErrorMessage()
    {
        $messages = [
            'Bof',
            'Pas du tout !',
            "Non, ce n'est pas ça",
            'Cherches encore',
            'Tu peux vraiment mieux faire.',
            "N'importe quoi !",
            "C'est tout ce que ça t'inspire ?",
            'Faut pas pousser mémé dans les orties !',
        ];
        $random_index = array_rand($messages);

        return $messages[$random_index];
    }
}
