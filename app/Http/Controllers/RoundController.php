<?php

namespace App\Http\Controllers;

use App\Events\NewScore;
use App\Events\TrackEnded;
use App\Jobs\ProcessScoreCreation;
use App\Models\Round;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RoundController extends Controller
{
    public function pause(Room $room, Round $round)
    {
        (Auth::user()->hasRoomControl($round->room))
            ? $room->pauseRound()
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
            $answers = [];
            $points = 0;

            foreach ($track->answers as $answer) {
                $value = sanitizeString($answer->value);

                if (str_contains($input, $value)) {
                    $similarity = 0;
                } else {
                    $similarity = levenshtein($input, $value);
                }

                $response[] = [
                    'type' => $answer->type->name,
                    'value' => $answer->value,
                    'similarity' => $similarity,
                    'compared' => $input.' => '.$value,
                    'score' => $answer->score,
                    'placeholder' => '',
                    'message' => '',
                ];

                if ($similarity < 4) {
                    $answers[] = $answer->id;
                    $points += $answer->score;

                    // Broadcast score
                    broadcast(new NewScore([
                        'room_id' => $round->room->id,
                        'user_id' => Auth::user()->id,
                        'answers' => $answers,
                        'points' => $points,
                        'total' => $total + $points,
                    ]));

                    // Store the score in database
                    ProcessScoreCreation::dispatch(Auth::user(), [
                        'round_id' => $round->id,
                        'track_id' => $track->id,
                        'answer_id' => $answer->id,
                        'score' => $answer->score,
                    ]);
                }
            }

            // Generate message

            // Return message to user ?
            //return response()->json($response, 200);
        }
    }
}
