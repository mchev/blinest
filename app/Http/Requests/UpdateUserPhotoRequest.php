<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPhotoRequest extends FormRequest
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
        return [
            'photo' => ['required', 'image', 'max:1024'],
        ];
    }
}
