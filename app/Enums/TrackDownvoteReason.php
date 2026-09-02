<?php

namespace App\Enums;

enum TrackDownvoteReason: string
{
    case SoundQuality = 'sound_quality';
    case Difficulty = 'difficulty';
    case PassageChoice = 'passage_choice';
    case PersonalTaste = 'personal_taste';
    case ControversialArtist = 'controversial_artist';
    case Other = 'other';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::SoundQuality => __('Poor sound quality'),
            self::Difficulty => __('Too difficult'),
            self::PassageChoice => __('Bad passage choice'),
            self::PersonalTaste => __('Not my taste'),
            self::ControversialArtist => __('Controversial artist'),
            self::Other => __('Other reason'),
        };
    }
}
