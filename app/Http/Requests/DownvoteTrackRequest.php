<?php

namespace App\Http\Requests;

use App\Enums\TrackDownvoteReason;
use App\Models\Track;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DownvoteTrackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'reason' => ['nullable', Rule::enum(TrackDownvoteReason::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $track = $this->route('track');

            if (! $track instanceof Track) {
                return;
            }

            if ($this->user()->hasDownvoted($track)) {
                return;
            }

            if (! $this->filled('reason')) {
                $validator->errors()->add('reason', __('A downvote reason is required.'));
            }
        });
    }

    public function reason(): ?TrackDownvoteReason
    {
        $reason = $this->validated('reason');

        if ($reason === null) {
            return null;
        }

        return TrackDownvoteReason::from($reason);
    }
}
