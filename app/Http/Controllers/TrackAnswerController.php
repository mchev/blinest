<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\TrackAnswer;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

use App\Services\MusicProvidersService as MusicProviders;

class TrackAnswerController extends Controller
{

    public function store(Track $track)
    {
        // VALIDATE
        Request::validate([
            'answer_type_id' => ['required', 'integer', 'exists:answer_types,id'],
            'value' => ['required', 'max:255'],
            'score' => ['required', 'numeric', 'min:0'],
        ]);

        // STORE
        $track->answers()->create([
            'answer_type_id' => Request::get('answer_type_id'),
            'value' => Request::get('value'),
            'score' => Request::get('score'),
        ]);

        return Redirect::back();

    }


    public function update(Track $track, TrackAnswer $trackAnswer)
    {
        // VALIDATE
        Request::validate([
            'answer_type_id' => ['required', 'integer', 'exists:answer_types,id'],
            'value' => ['required', 'max:255'],
            'score' => ['required', 'numeric', 'min:0'],
        ]);

        // UPDATE
        $trackAnswer->update([
            'answer_type_id' => Request::get('answer_type_id'),
            'value' => Request::get('value'),
            'score' => Request::get('score'),
        ]);

        return Redirect::back();
    }

    public function destroy(TrackAnswer $answer)
    {
        $answer->delete();
        return Redirect::back();
    }

}
