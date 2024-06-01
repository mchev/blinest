<?php

namespace App\Rules;

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
    }
}
