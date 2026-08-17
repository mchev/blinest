import { computed, unref } from 'vue'

const POSTER_GRADIENTS = ['from-brand-deep to-brand-midnight', 'from-brand-midnight to-brand-deep', 'from-brand-deep via-brand-midnight to-brand-deep', 'from-brand-midnight via-brand-deep to-brand-midnight', 'from-brand-deep-hover to-brand-midnight', 'from-brand-midnight to-brand-deep-hover', 'from-brand-deep to-brand-deep-hover', 'from-brand-deep-hover to-brand-deep']

export function useRoomPoster(roomSource) {
  const room = computed(() => unref(roomSource))

  const hasCustomPhoto = computed(() => Boolean(room.value?.photo_path))

  const posterGradient = computed(() => {
    const key = room.value?.category?.id ?? room.value?.id ?? 0

    return POSTER_GRADIENTS[Math.abs(Number(key)) % POSTER_GRADIENTS.length]
  })

  const posterInitials = computed(() => {
    const words = String(room.value?.name ?? '')
      .trim()
      .split(/\s+/)
      .filter(Boolean)

    if (words.length >= 2) {
      return `${words[0][0] ?? ''}${words[1][0] ?? ''}`.toUpperCase()
    }

    return String(room.value?.name ?? '?')
      .slice(0, 2)
      .toUpperCase()
  })

  return {
    hasCustomPhoto,
    posterGradient,
    posterInitials,
  }
}
