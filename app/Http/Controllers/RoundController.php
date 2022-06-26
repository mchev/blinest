<?php

namespace App\Http\Controllers;

use App\Models\Round;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RoundController extends Controller
{

    public function check(Round $round, Track $track)
    {

        if ($round->tracks[$round->current - 1] != $track->id) {
            return response()->json('Too late.');
        }

        Request::validate([
            'text' => 'required|string|min:2',
        ]);

        $input = Request::input('text');

        $keys = [];
        $values = [];
        $ids = [];
        $scores = [];
        $similarities = [];

        foreach($track->answers as $answer) {

            // Similarity in percent
            $similarity = similarity($input, $answer->value);

            //if($similarity > 80) {
                $keys[] = $answer->type->name;
                $values[] = $answer->value;
                $ids[] = $answer->id;
                $scores[] = $answer->score;
                $similarities[] = $similarity;
            //}

        }

        $score = array_sum($scores);

        return response()->json([
            'keys' => $keys,
            'values' => $values,
            'similarities' => $similarities,
            'ids' => $ids,
            'scores' => $scores,
            'score' => $score,
        ], 200);

    }

}
