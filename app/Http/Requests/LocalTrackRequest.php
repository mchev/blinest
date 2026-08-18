<?php

namespace App\Http\Requests;

use App\Rules\AudioDuration;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LocalTrackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isPublicModerator();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'playlist_id' => ['nullable', 'integer', 'exists:playlists,id'],
            'artist_name' => ['required', 'max:255'],
            'track_name' => ['required', 'max:255', 'unique:local_tracks,track_name,NULL,id,artist_name,'.$this->artist_name],
            'audio' => ['required', 'file', 'mimes:mp3', 'max:1024', new AudioDuration(30)],
            'artwork' => ['required', 'file', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'track_name.unique' => 'Cette chanson existe déjà dans la base de Blinest.',
            'audio.mimes' => 'Le fichier audio doit être au format MP3.',
            'artwork.mimes' => 'La pochette de l\'album doit être au format PNG, JPG, JPEG ou WEBP.',
        ];
    }
}
