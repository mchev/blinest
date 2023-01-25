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
