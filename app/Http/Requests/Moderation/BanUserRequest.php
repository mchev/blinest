<?php

namespace App\Http\Requests\Moderation;

use Illuminate\Foundation\Http\FormRequest;

class BanUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isPublicModerator() || $this->user()->isAdministrator();
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:1'], // en minutes
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'La raison du bannissement est requise.',
            'reason.max' => 'La raison ne peut pas dépasser 255 caractères.',
            'duration.min' => 'La durée minimale doit être de 1 minute.',
        ];
    }
}
