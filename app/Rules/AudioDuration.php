<?php

namespace App\Rules;

use Closure;
use getID3;
use Illuminate\Contracts\Validation\ValidationRule;

class AudioDuration implements ValidationRule
{
    public function __construct(protected int $seconds)
    {
        $this->seconds = $seconds;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mp3file = new getID3;
        $fileInfo = $mp3file->analyze($value->getPathname());

        if (! isset($fileInfo['playtime_seconds']) || abs($fileInfo['playtime_seconds'] - $this->seconds) > 1) {
            $fail('Le fichier audio doit durer précisément '.$this->seconds.' secondes.');
        }
    }
}
