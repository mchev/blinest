<script setup>
import { ref, computed, toRef } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PlayingIcon from '@/Components/PlayingIcon.vue'
import { useRoomPublicChannel } from '@/composables/useRoomPublicChannel'
import { useRoomPoster } from '@/composables/useRoomPoster'

const props = defineProps({
    room: {
        type: Object,
        required: true,
    },
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'featured', 'catalog'].includes(value),
    },
})

const page = usePage()
const roomRef = toRef(props, 'room')
const { hasCustomPhoto, posterGradient, posterInitials } = useRoomPoster(roomRef)

function t(key, replace = {}) {
    let translation = page.props.language?.[key] ?? key

    Object.entries(replace).forEach(([placeholder, value]) => {
        translation = translation.replace(`:${placeholder}`, String(value))
    })

    return translation
}

const cardRef = ref(null)

const {
    memberCount,
    isPlaying,
    currentTrackIndex,
    tracksByRound,
    progressPercent,
    isNearEnd,
    cardState,
    memberCountBump,
    visibleTrackDots,
} = useRoomPublicChannel(props.room, cardRef)

const statusLabel = computed(() => {
    switch (cardState.value) {
        case 'hot':
            return t('Room in full swing')
        case 'live':
            return t('Game in progress')
        case 'live-empty':
            return t('Waiting for players')
        case 'lobby-manual':
            return t('Waiting for host')
        default:
            return t('Open room')
    }
})

const ctaLabel = computed(() => {
    switch (cardState.value) {
        case 'hot':
        case 'live':
            return t('Join now')
        case 'live-empty':
            return t('Be the first to join')
        case 'lobby-manual':
            return t('Enter room')
        default:
            return t('Play')
    }
})

const ctaClass = computed(() => {
    switch (cardState.value) {
        case 'hot':
        case 'live':
            return 'game-btn-play-live'
        case 'live-empty':
            return 'game-btn-play-join'
        default:
            return 'game-btn-play-neutral'
    }
})

const isPasswordProtected = computed(() => Boolean(props.room.password))
const isPrivateRoom = computed(() => !props.room.is_public)
const isFeatured = computed(() => props.variant === 'featured')
const isCatalog = computed(() => props.variant === 'catalog')

const roomHref = computed(() => `/rooms/${props.room.slug}`)

const cardRingClass = computed(() => {
    switch (cardState.value) {
        case 'hot':
            return 'ring-red-500/50 shadow-red-900/30'
        case 'live':
            return 'ring-emerald-500/40 shadow-emerald-900/20'
        case 'live-empty':
            return 'ring-amber-500/30'
        default:
            return 'ring-zinc-700/60'
    }
})

const statusBadgeClass = computed(() => {
    switch (cardState.value) {
        case 'hot':
        case 'live':
            return null
        case 'live-empty':
            return 'border-amber-400/50 bg-amber-500/90 text-white'
        case 'lobby-manual':
            return 'border-sky-400/40 bg-sky-600/90 text-white'
        default:
            return 'border-zinc-600/60 bg-zinc-800/90 text-zinc-200'
    }
})

function dotFilled(index) {
    return index < currentTrackIndex.value
}

function dotActive(index) {
    return isPlaying.value && index === currentTrackIndex.value - 1
}
</script>

<template>
    <article ref="cardRef" class="group flex h-full w-full flex-col">
        <div
            class="game-card flex h-full flex-col shadow-lg"
            :class="cardRingClass"
        >
            <Link
                :href="roomHref"
                :rel="isPrivateRoom ? 'nofollow' : undefined"
                prefetch="hover"
                class="relative block overflow-hidden"
                :class="isFeatured ? 'aspect-[16/10]' : 'aspect-[16/9]'"
            >
                <img
                    v-if="hasCustomPhoto"
                    :src="room.photo"
                    :alt="room.name"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    loading="lazy"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center bg-gradient-to-br transition-transform duration-500 group-hover:scale-[1.02]"
                    :class="posterGradient"
                    aria-hidden="true"
                >
                    <span class="select-none text-4xl font-black tracking-tighter text-white/20 sm:text-5xl">
                        {{ posterInitials }}
                    </span>
                </div>

                <div
                    class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-black/10"
                    :class="{ 'opacity-70': isCatalog }"
                />

                <div
                    v-if="isPlaying"
                    class="pointer-events-none absolute inset-x-0 top-2 flex justify-center opacity-90"
                >
                    <PlayingIcon class="h-8 w-14 sm:h-10 sm:w-16" />
                </div>

                <div class="absolute left-2.5 top-2.5 flex flex-wrap gap-1.5">
                    <span v-if="isPlaying" class="live-pill">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-white" aria-hidden="true" />
                        LIVE
                    </span>
                    <span
                        v-else-if="statusBadgeClass"
                        class="inline-flex items-center rounded px-2 py-0.5 text-[11px] font-black uppercase tracking-wide backdrop-blur-sm"
                        :class="statusBadgeClass"
                    >
                        {{ statusLabel }}
                    </span>
                    <span
                        v-if="isNearEnd"
                        class="inline-flex items-center rounded bg-orange-500 px-2 py-0.5 text-[11px] font-black uppercase tracking-wide text-white"
                    >
                        {{ t('Final tracks') }}
                    </span>
                </div>

                <div class="absolute right-2.5 top-2.5 flex gap-1">
                    <span
                        v-if="isPasswordProtected"
                        class="flex h-7 w-7 items-center justify-center rounded-md border border-orange-400/40 bg-orange-500 text-white shadow-lg"
                        :title="t('Password protected')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span
                        v-if="!room.is_autostart"
                        class="flex h-7 w-7 items-center justify-center rounded-md border border-amber-400/40 bg-amber-500 text-white shadow-lg"
                        :title="t('Manual start')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5" aria-hidden="true">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div v-if="!isCatalog" class="absolute bottom-0 left-0 right-0 p-3">
                    <h3
                        class="truncate text-base font-bold tracking-tight text-white drop-shadow-sm sm:text-lg"
                        :title="room.name"
                    >
                        {{ room.name }}
                    </h3>
                    <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs font-semibold text-zinc-200">
                        <span
                            v-if="room.category"
                            class="rounded bg-zinc-900/70 px-1.5 py-0.5 text-[10px] font-semibold text-zinc-300"
                        >
                            {{ __(room.category.name) }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1 transition-transform duration-300"
                            :class="{ 'text-emerald-300': memberCountBump }"
                        >
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M2 22C2 17.5817 5.58172 14 10 14C14.4183 14 18 17.5817 18 22H16C16 18.6863 13.3137 16 10 16C6.68629 16 4 18.6863 4 22H2ZM10 13C6.685 13 4 10.315 4 7C4 3.685 6.685 1 10 1C13.315 1 16 3.685 16 7C16 10.315 13.315 13 10 13Z" />
                            </svg>
                            {{ memberCount }} {{ t('online') }}
                        </span>
                        <span class="text-zinc-500" aria-hidden="true">·</span>
                        <span>
                            <template v-if="isPlaying">
                                {{ t('Track :current / :total', { current: currentTrackIndex, total: tracksByRound }) }}
                            </template>
                            <template v-else>
                                {{ tracksByRound }} {{ t('tracks') }}
                            </template>
                        </span>
                    </div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 h-1 bg-black/60">
                    <div
                        class="h-full bg-gradient-to-r from-red-600 via-red-500 to-orange-400 transition-all duration-500 ease-out"
                        :style="{ width: `${progressPercent}%` }"
                    />
                </div>
            </Link>

            <div class="flex flex-1 flex-col gap-2.5 p-3" :class="{ 'gap-2': isCatalog }">
                <template v-if="isCatalog">
                    <div class="min-w-0">
                        <h3
                            class="truncate text-sm font-bold text-white sm:text-base"
                            :title="room.name"
                        >
                            {{ room.name }}
                        </h3>
                        <p class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-zinc-400">
                            <span
                                v-if="room.category"
                                class="font-medium text-zinc-300"
                            >
                                {{ __(room.category.name) }}
                            </span>
                            <span v-if="room.category" class="text-zinc-600" aria-hidden="true">·</span>
                            <span class="inline-flex items-center gap-1" :class="{ 'text-emerald-400': memberCountBump }">
                                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M2 22C2 17.5817 5.58172 14 10 14C14.4183 14 18 17.5817 18 22H16C16 18.6863 13.3137 16 10 16C6.68629 16 4 18.6863 4 22H2ZM10 13C6.685 13 4 10.315 4 7C4 3.685 6.685 1 10 1C13.315 1 16 3.685 16 7C16 10.315 13.315 13 10 13Z" />
                                </svg>
                                {{ memberCount }}
                            </span>
                            <span class="text-zinc-600" aria-hidden="true">·</span>
                            <span>
                                <template v-if="isPlaying">
                                    {{ t('Track :current / :total', { current: currentTrackIndex, total: tracksByRound }) }}
                                </template>
                                <template v-else>
                                    {{ tracksByRound }} {{ t('tracks') }}
                                </template>
                            </span>
                        </p>
                    </div>
                </template>

                <p
                    v-else-if="isFeatured && room.description"
                    class="line-clamp-2 text-sm leading-relaxed text-zinc-300"
                >
                    {{ room.description }}
                </p>

                <div
                    v-if="isPrivateRoom && room.owner"
                    class="flex items-center gap-2"
                >
                    <img
                        :src="room.owner.photo"
                        :alt="room.owner.name"
                        class="h-5 w-5 rounded-full ring-2 ring-zinc-600"
                        loading="lazy"
                    />
                    <span class="truncate text-xs font-medium text-zinc-300">{{ room.owner.name }}</span>
                </div>

                <div
                    v-if="!isCatalog"
                    class="flex h-1.5 items-center gap-1"
                    :class="{ invisible: !isPlaying }"
                    aria-hidden="true"
                >
                    <span
                        v-for="index in visibleTrackDots"
                        :key="`dot-${room.id}-${index}`"
                        class="h-1.5 flex-1 rounded-full transition-all duration-300"
                        :class="[
                            dotActive(index - 1)
                                ? 'bg-red-400 shadow-[0_0_6px_rgba(248,113,113,0.9)]'
                                : dotFilled(index - 1)
                                    ? 'bg-red-500'
                                    : 'bg-zinc-700',
                        ]"
                    />
                </div>

                <Link
                    :href="roomHref"
                    prefetch="hover"
                    class="mt-auto"
                    :class="[ctaClass, isCatalog ? 'min-h-[38px] text-xs' : '']"
                >
                    {{ ctaLabel }}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </article>
</template>
