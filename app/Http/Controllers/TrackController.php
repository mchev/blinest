<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

use App\Services\MusicProvidersService as MusicProviders;

class TrackController extends Controller
{

    public function index(Playlist $playlist)
    {
        return response()->json([
            'filters' => Request::all('search'),
            'tracks' => $playlist->tracks()
                ->orderBy('updated_at')
                ->filter(Request::only('search'))
                ->get()
                ->transform(fn ($team) => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'owner' => $team->owner,
                    'photo' => $team->photo_path ? URL::route('image', ['path' => $team->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $team->deleted_at,
                ]),
        ]);
    }

    public function search()
    {

        return response()->json([
            'filters' => Request::only('term'),
            'tracks' => (new MusicProviders)->search(Request::get('term')),
        ]);
    }
}
