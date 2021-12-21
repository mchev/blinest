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
            'key' => ['required', 'string', 'max:50'],
            'value' => ['required', 'max:255'],
            'score' => ['required', 'numeric', 'min:0'],
        ]);

        // STORE
        $track->answers()->create([
            'key' => Request::get('key'),
            'value' => Request::get('value'),
            'score' => Request::get('score'),
        ]);

        return Redirect::back();

    }


    public function update(Track $track, TrackAnswer $answer)
    {
        // VALIDATE
        Request::validate([
            'key' => ['required', 'string', 'max:50'],
            'value' => ['required', 'max:255'],
            'score' => ['required', 'numeric', 'min:0'],
        ]);

        // UPDATE
        $answer->update([
            'key' => Request::get('key'),
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
