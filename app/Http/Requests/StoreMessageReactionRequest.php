<?php

namespace App\Http\Requests;

use App\Services\Chat\ChatReactionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreMessageReactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && ! $user->isGuest();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'emoji' => ['required', 'string', 'max:191'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $emoji = $this->string('emoji')->toString();

            if ($emoji === '') {
                return;
            }

            $reactions = app(ChatReactionService::class);

            if (! $reactions->isAllowedEmoji($emoji)) {
                $validator->errors()->add('emoji', __('Invalid reaction emoji'));

                return;
            }

            if (! $reactions->canUserReactWith($this->user(), $emoji)) {
                $validator->errors()->add('emoji', __('Supporter reaction emoji locked'));
            }
        });
    }
}
