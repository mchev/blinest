<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Reserved;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
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
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name' => ['required', 'min:2', 'max:25', Rule::unique('users')->ignore($user->id), new Reserved($user->name)],
            'email' => ['required', 'max:255', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
        ];
    }
}
