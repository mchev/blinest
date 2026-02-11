<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinigameScore extends Model
{
    public const TYPE_QUIZ = 'quiz';

    public const TYPE_WHO_SANG = 'who_sang';

    public const TYPE_ALBUM_COVER = 'album_cover';

    public const TYPE_FIRST_LETTER = 'first_letter';

    public const TYPE_ANAGRAM = 'anagram';

    protected $fillable = [
        'user_id',
        'game_type',
        'score',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    public static function typeLabels(): array
    {
        return [
            self::TYPE_QUIZ => 'Quiz à 4 choix',
            self::TYPE_WHO_SANG => 'Qui a chanté ?',
            self::TYPE_ALBUM_COVER => 'Pochette d\'album',
            self::TYPE_FIRST_LETTER => 'Première lettre',
            self::TYPE_ANAGRAM => 'Anagramme',
        ];
    }
}
