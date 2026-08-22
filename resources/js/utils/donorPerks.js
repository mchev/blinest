/** @typedef {'ad_free'|'avatar_crown'|'custom_name_style'|'solo_elo'} DonorPerkKey */

/**
 * @param {{ donor_perks?: DonorPerkKey[], is_supporter?: boolean }|null|undefined} user
 * @param {DonorPerkKey} perk
 */
export function userHasDonorPerk(user, perk) {
  if (Array.isArray(user?.donor_perks)) {
    return user.donor_perks.includes(perk)
  }

  return Boolean(user?.is_supporter) && (perk === 'ad_free' || perk === 'avatar_crown')
}

/**
 * @param {{ donor_perks?: DonorPerkKey[], is_supporter?: boolean }|null|undefined} user
 */
export function userHasDonorCrown(user) {
  return userHasDonorPerk(user, 'avatar_crown')
}
