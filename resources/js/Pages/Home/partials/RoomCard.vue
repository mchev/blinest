<script setup>
import { ref, computed, toRef } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PlayingIcon from '@/Components/PlayingIcon.vue'
import Icon from '@/Components/Icon.vue'
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

const { memberCount, isPlaying, currentTrackIndex, tracksByRound, progressPercent, isNearEnd, cardState, memberCountBump, visibleTrackDots } = useRoomPublicChannel(props.room, cardRef)

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

const isPlayingWithPlayers = computed(() => isPlaying.value && memberCount.value >= 1)
const isPlayingEmpty = computed(() => isPlaying.value && memberCount.value < 1)

const ctaClass = computed(() => {
  switch (cardState.value) {
    case 'hot':
    case 'live':
      return 'game-btn-play-hot'
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

const trackStatLabel = computed(() => {
  if (isPlaying.value) {
    return t('Track :current / :total', { current: currentTrackIndex.value, total: tracksByRound.value })
  }

  if (isPrivateRoom.value && props.room.tracks_count != null) {
    return `${props.room.tracks_count} ${t('tracks')}`
  }

  return `${tracksByRound.value} ${t('tracks')}`
})

const trackStatValue = computed(() => {
  if (isPlaying.value) {
    return `${currentTrackIndex.value}/${tracksByRound.value}`
  }

  if (isPrivateRoom.value && props.room.tracks_count != null) {
    return String(props.room.tracks_count)
  }

  return String(tracksByRound.value)
})

const onlineStatLabel = computed(() => `${memberCount.value} ${t('online')}`)

function dotFilled(index) {
  return index < currentTrackIndex.value
}

function dotActive(index) {
  return isPlaying.value && index === currentTrackIndex.value - 1
}
</script>

<template>
  <article ref="cardRef" class="group flex h-full w-full flex-col">
    <div class="game-card flex h-full flex-col" :class="{ 'game-card--live': isPlayingEmpty, 'game-card--hot': isPlayingWithPlayers }">
      <Link :href="roomHref" :rel="isPrivateRoom ? 'nofollow' : undefined" prefetch="hover" class="relative block overflow-hidden" :class="isFeatured ? 'aspect-[16/10]' : 'aspect-[16/9]'">
        <img v-if="hasCustomPhoto" :src="room.photo" :alt="room.name" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" />
        <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br transition-transform duration-700 group-hover:scale-105" :class="posterGradient" aria-hidden="true">
          <span class="game-card__poster-grid" aria-hidden="true" />
          <span class="select-none text-5xl font-black tracking-tighter text-white/[0.07] sm:text-6xl">
            {{ posterInitials }}
          </span>
        </div>

        <span class="game-card__scanlines" aria-hidden="true" />

        <div class="absolute inset-0 bg-gradient-to-t from-brand-midnight via-brand-midnight/50 to-transparent" />

        <div v-if="isPlaying" class="pointer-events-none absolute inset-x-0 top-3 flex justify-center" :class="isPlayingWithPlayers ? 'text-brand-secondary' : 'text-brand-primary'">
          <PlayingIcon class="h-8 w-14 drop-shadow-lg sm:h-10 sm:w-16" />
        </div>

        <div class="absolute left-2.5 top-2.5 z-20 flex flex-wrap gap-1.5">
          <span v-if="isPlaying" class="live-pill" :class="{ 'live-pill--hot': isPlayingWithPlayers }">
            <span class="live-pill__dot" :class="{ 'live-pill__dot--hot': isPlayingWithPlayers }" aria-hidden="true" />
            LIVE
          </span>
          <span v-else class="retro-badge">
            {{ statusLabel }}
          </span>
          <span v-if="isNearEnd" class="retro-badge">
            {{ t('Final tracks') }}
          </span>
        </div>

        <div class="absolute right-2.5 top-2.5 z-20 flex gap-1">
          <span v-if="isPasswordProtected" class="retro-badge flex h-7 w-7 items-center justify-center p-0" :aria-label="t('Password protected')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5" aria-hidden="true">
              <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
            </svg>
          </span>
          <span v-if="!room.is_autostart" class="retro-badge flex h-7 w-7 items-center justify-center p-0" :aria-label="t('Manual start')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5" aria-hidden="true">
              <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
            </svg>
          </span>
        </div>

        <div v-if="!isCatalog" class="absolute bottom-0 left-0 right-0 z-20 p-3">
          <h3 class="truncate text-base font-bold uppercase tracking-wide text-white sm:text-lg" :title="room.name">
            {{ room.name }}
          </h3>
          <div class="game-card__stats">
            <span class="retro-stat retro-stat--card" :class="{ 'scale-110': memberCountBump }" :aria-label="onlineStatLabel" :title="onlineStatLabel">
              <Icon name="users" class="h-4 w-4 shrink-0" aria-hidden="true" />
              <span class="tabular-nums">{{ memberCount }}</span>
            </span>
            <span class="retro-stat retro-stat--card" :aria-label="trackStatLabel" :title="trackStatLabel">
              <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
              </svg>
              <span class="tabular-nums">{{ trackStatValue }}</span>
            </span>
          </div>
        </div>

        <div class="retro-progress absolute bottom-0 left-0 right-0 z-20">
          <div
            class="retro-progress__fill"
            :class="{
              'retro-progress__fill--live': isPlayingEmpty,
              'retro-progress__fill--hot': isPlayingWithPlayers,
            }"
            :style="{ width: `${progressPercent}%` }"
          />
        </div>
      </Link>

      <div class="game-card__deck flex flex-1 flex-col gap-3">
        <template v-if="isCatalog">
          <div class="min-w-0">
            <h3 class="truncate text-sm font-bold uppercase tracking-wide text-white sm:text-base" :title="room.name">
              {{ room.name }}
            </h3>
            <div class="game-card__stats">
              <span class="retro-stat retro-stat--card" :class="{ 'scale-110': memberCountBump }" :aria-label="onlineStatLabel" :title="onlineStatLabel">
                <Icon name="users" class="h-4 w-4 shrink-0" aria-hidden="true" />
                <span class="tabular-nums">{{ memberCount }}</span>
              </span>
              <span class="retro-stat retro-stat--card" :aria-label="trackStatLabel" :title="trackStatLabel">
                <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
                <span class="tabular-nums">{{ trackStatValue }}</span>
              </span>
            </div>
          </div>
        </template>

        <p v-else-if="isFeatured && room.description" class="line-clamp-2 text-sm leading-relaxed text-white/70">
          {{ room.description }}
        </p>

        <div v-if="isPrivateRoom && room.owner" class="flex items-center gap-2">
          <img :src="room.owner.photo" :alt="room.owner.name" class="h-5 w-5 rounded-full ring-2 ring-white/20" loading="lazy" />
          <span class="truncate text-xs text-white/75">{{ room.owner.name }}</span>
        </div>

        <div v-if="!isCatalog && isPlaying" class="flex h-1 items-center gap-0.5" aria-hidden="true">
          <span v-for="index in visibleTrackDots" :key="`dot-${room.id}-${index}`" class="h-1 flex-1 transition-all duration-300" :class="[dotActive(index - 1) ? 'bg-white shadow-[0_0_6px_rgb(255_255_255/0.8)]' : dotFilled(index - 1) ? 'bg-white/50' : 'bg-white/15']" />
        </div>

        <Link :href="roomHref" prefetch="hover" class="mt-auto" :class="ctaClass">
          {{ ctaLabel }}
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
          </svg>
        </Link>
      </div>
    </div>
  </article>
</template>
