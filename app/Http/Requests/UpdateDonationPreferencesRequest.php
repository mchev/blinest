<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDonationPreferencesRequest extends FormRequest
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
            'show_donation_history_on_profile' => ['required', 'boolean'],
        ];
    }
}
