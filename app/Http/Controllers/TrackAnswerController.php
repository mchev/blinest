<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\TrackAnswer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class TrackAnswerController extends Controller
{
    public function store(Track $track)
    {
        Request::validate([
            'answer_type_id' => ['required', 'integer', 'exists:answer_types,id'],
            'value' => ['required', 'max:255'],
            'score' => ['required', 'numeric', 'min:0', 'max:99'],
            'aliases' => ['nullable', 'array', 'max:20'],
            'aliases.*' => ['nullable', 'string', 'max:255'],
        ]);

        $aliases = $this->normalizeAliasesInput(Request::input('aliases'));

        $track->answers()->create([
            'answer_type_id' => Request::get('answer_type_id'),
            'value' => Request::get('value'),
            'score' => Request::get('score'),
            'aliases' => $aliases === [] ? null : $aliases,
        ]);

        Cache::forget(Track::answersCacheKey($track->id));

        return Redirect::back();
    }

    public function update(Track $track, TrackAnswer $answer)
    {
        Request::validate([
            'answer_type_id' => ['required', 'integer', 'exists:answer_types,id'],
            'value' => ['required', 'max:255'],
            'score' => ['required', 'numeric', 'min:0', 'max:99'],
            'aliases' => ['nullable', 'array', 'max:20'],
            'aliases.*' => ['nullable', 'string', 'max:255'],
        ]);

        $aliases = $this->normalizeAliasesInput(Request::input('aliases'));

        $answer->update([
            'answer_type_id' => Request::get('answer_type_id'),
            'value' => Request::get('value'),
            'score' => Request::get('score'),
            'aliases' => $aliases === [] ? null : $aliases,
        ]);

        Cache::forget(Track::answersCacheKey($track->id));

        return Redirect::back();
    }

    public function destroy(Track $track, TrackAnswer $answer)
    {
        $answer->delete();

        Cache::forget(Track::answersCacheKey($track->id));

        return Redirect::back();
    }

    /**
     * @param  array<int, mixed>|null  $aliases
     * @return list<string>
     */
    private function normalizeAliasesInput(?array $aliases): array
    {
        if ($aliases === null) {
            return [];
        }

        return collect($aliases)
            ->filter(fn ($line) => is_string($line) && trim($line) !== '')
            ->map(fn (string $line) => trim($line))
            ->unique()
            ->values()
            ->all();
    }
}
