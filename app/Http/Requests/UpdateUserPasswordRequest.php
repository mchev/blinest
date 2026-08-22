<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UpdateUserPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->id === $this->route('user')?->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($this->user()?->hasPassword()) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        return $rules;
    }
}
