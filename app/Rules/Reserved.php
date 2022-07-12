<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Reserved implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $reserved = [
            'admin',
            'admins',
            'administrateur',
            'administrator',
            'superadmin',
            'blinest',
            'blinest.com',
            'moderator',
            'moderateur',
            'test',
            'playlist',
            'room',
            'team',
            'mchev',
        ];

        if (in_array(strtolower($value), $reserved)) {
            $fail('validation.reserved')->translate();
        }
    }
}
