<?php

namespace App\Enums;

enum DonorPerk: string
{
    case AdFree = 'ad_free';
    case AvatarCrown = 'avatar_crown';

    case SupporterReactions = 'supporter_reactions';

    /** Reserved for future rollout — enable in config when implemented. */
    case CustomNameStyle = 'custom_name_style';

    /** Reserved for future rollout — enable in config when implemented. */
    case SoloElo = 'solo_elo';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
