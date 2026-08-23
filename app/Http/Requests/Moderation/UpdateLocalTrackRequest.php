<?php

namespace App\Http\Requests\Moderation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocalTrackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPublicModerator() || $this->user()?->isAdministrator();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $localTrack = $this->route('localTrack');

        return [
            'track_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('local_tracks', 'track_name')
                    ->where('artist_name', $this->input('artist_name'))
                    ->ignore($localTrack?->id),
            ],
            'artist_name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'track_name.unique' => __('Moderation local track duplicate'),
        ];
    }
}
