<?php

namespace App\Rules;

use App\Models\Room;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Reserved implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $reserved = [
            'admin',
            'admins',
            'mchev',
            'administrateur',
            'administrator',
            'superadmin',
            'blinest.com',
            'moderator',
            'moderateur',
            'test',
            'playlist',
            'room',
            'team',
        ];

        // Reserved names
        if (in_array(strtolower($value), $reserved)) {
            $fail('validation.reserved')->translate();
        }

        // Bad words filter
        foreach (trans('bad-words') as $badword) {
            if (stripos(strtolower($value), $badword) !== false) {
                $fail('validation.reserved')->translate();
            }
        }

        // Reserved moderators names
        $names = Room::isPublic()
            ->with('moderators:name')
            ->get()
            ->pluck('moderators.*.name')
            ->flatten()
            ->map(fn ($name) => strtolower($name))
            ->unique()
            ->values()
            ->all();
        $names[] = 'mchev';

        foreach ($names as $name) {
            if (str_contains(strtolower($value), $name)) {
                $fail('validation.reserved')->translate();
                break;
            }
        }

    }
}
