import { computed, unref } from 'vue'

const POSTER_GRADIENTS = [
    'from-red-950 via-red-900/70 to-zinc-950',
    'from-violet-950 via-violet-900/70 to-zinc-950',
    'from-sky-950 via-sky-900/70 to-zinc-950',
    'from-emerald-950 via-emerald-900/70 to-zinc-950',
    'from-amber-950 via-amber-900/70 to-zinc-950',
    'from-rose-950 via-rose-900/70 to-zinc-950',
    'from-indigo-950 via-indigo-900/70 to-zinc-950',
    'from-teal-950 via-teal-900/70 to-zinc-950',
]

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

        return String(room.value?.name ?? '?').slice(0, 2).toUpperCase()
    })

    return {
        hasCustomPhoto,
        posterGradient,
        posterInitials,
    }
}
