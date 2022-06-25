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

        Request::validate([
            'text' => 'required|string|min:2',
        ]);

        $lev = [];

        foreach($track->answers as $answer) {
            $lev[] = [
                'lev' => levenshtein(strtolower(Request::input('text')), strtolower($answer->value)),
                'key' => $answer->key,
                'value' => $answer->value,
            ];
        }

        dd($lev);

        $message = "Bravo! Tu as trouvé le titre.";

        return response()->json([
            'message' => $message,
        ], 200);

    }

}
