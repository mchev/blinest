<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref, watch, onMounted, onUnmounted, computed } from 'vue'
import { TransitionGroup } from 'vue'
import axios from 'axios'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import AnswerCardMobile from './AnswerCardMobile.vue'
import AnswerCardDesktop from './AnswerCardDesktop.vue'

const props = defineProps({
  users: Array,
  channel: String,
  /** Synced from Show.vue (join + Echo): used for playlist progress */
  round: {
    type: Object,
    default: null,
  },
  /** Room id for vote URLs (round from join may omit nested room) */
  roomId: {
    type: Number,
    required: true,
  },
  /** Extraits déjà terminés (réponse GET /joined), ordre chronologique — affichage comme après TrackEnded (récent en haut) */
  initialPlayedTracks: {
    type: Array,
    default: () => [],
  },
  compact: {
    type: Boolean,
    default: false,
  },
})

const user = usePage().props.auth.user
const tracks = ref([])
let playlistChannel = null

const progressPercentage = computed(() => {
  if (!props.round?.tracks?.length) {
    return 0
  }

  return Math.round((props.round.current / props.round.tracks.length) * 100)
})

// Add computed property for track count
const trackCount = computed(() => {
  return `${props.round?.current || 0} / ${props.round?.tracks?.length || 0}`
})

watch(
  () => props.initialPlayedTracks,
  (list) => {
    if (!list || list.length === 0) {
      return
    }
    if (tracks.value.length !== 0) {
      return
    }
    tracks.value = [...list].reverse()
  },
  { deep: true, immediate: true },
)

onMounted(() => {
  if (!props.channel) {
    return
  }

  playlistChannel = Echo.join(props.channel)
  playlistChannel
    .listen('RoundStarted', () => {
      tracks.value = []
    })
    .listen('TrackEnded', (e) => {
      tracks.value.unshift(e.track)
    })
    .listen('TrackVoted', (e) => {
      const index = tracks.value.findIndex((x) => x.id === e.track.id)
      if (index !== -1) {
        tracks.value[index].downvotes = e.track.downvotes
        tracks.value[index].upvotes = e.track.upvotes
      }
    })
})

onUnmounted(() => {
  if (!playlistChannel) {
    return
  }

  playlistChannel.stopListening('RoundStarted')
  playlistChannel.stopListening('TrackEnded')
  playlistChannel.stopListening('TrackVoted')
  playlistChannel = null
})

const voteTrackDown = (track) => {
  if (!props.roomId) return
  axios.post(`/rooms/${props.roomId}/tracks/${track.id}/downvote`).catch((error) => console.error('Error downvoting track:', error))
}

const voteTrackUp = (track) => {
  if (!props.roomId) return
  axios.post(`/rooms/${props.roomId}/tracks/${track.id}/upvote`).catch((error) => console.error('Error upvoting track:', error))
}

/** Playlist : historique des extraits uniquement (pas d’affichage des scores / bonnes réponses). */
const getUserAnswerForTrackAndAnswer = () => null
</script>
<template>
  <Card :class="compact ? 'room-playlist-card--compact flex h-full min-h-0 flex-col' : ''">
    <template #header>
      <div class="flex w-full flex-wrap items-center justify-between gap-2">
        <h3 class="retro-title retro-title--accent flex items-center" :class="compact ? 'text-sm' : 'text-base md:text-xl'" role="heading" aria-level="2">
          <Icon name="music" class="mr-2 h-4 w-4 md:h-5 md:w-5" aria-hidden="true" />
          {{ __('Playlist') }}
        </h3>
        <div v-if="round" class="flex items-center gap-2">
          <div class="text-xs font-medium text-white/60" aria-label="Track progress">{{ __('Track') }} {{ trackCount }}</div>
          <div class="h-1.5 w-16 bg-brand-midnight md:w-32" role="progressbar" :aria-valuenow="progressPercentage" aria-valuemin="0" aria-valuemax="100">
            <div class="h-1.5 bg-brand-accent transition-all duration-300" :style="`width: ${progressPercentage}%`"></div>
          </div>
        </div>
      </div>
    </template>

    <div class="overflow-y-auto pr-2" :class="compact ? 'room-playlist-scroll--compact min-h-0 flex-1' : 'h-64 md:h-80 2xl:h-96'" style="scrollbar-width: thin; scrollbar-color: rgb(0 173 181 / 0.5) rgb(26 26 46 / 0.5)" role="list" aria-label="Played tracks">
      <component :is="compact ? 'ul' : TransitionGroup" :name="compact ? undefined : 'flip-list'" tag="ul" :class="compact ? 'space-y-1.5' : 'space-y-3 md:space-y-4'">
        <template v-for="(track, index) in tracks" :key="track.id">
          <!-- Mobile version -->
          <AnswerCardMobile :track="track" :getUserAnswerForTrackAndAnswer="getUserAnswerForTrackAndAnswer" :voteTrackUp="voteTrackUp" :voteTrackDown="voteTrackDown" :user="user" :is-latest="index === 0" class="md:hidden" />

          <!-- Desktop version -->
          <AnswerCardDesktop :track="track" :getUserAnswerForTrackAndAnswer="getUserAnswerForTrackAndAnswer" :voteTrackUp="voteTrackUp" :voteTrackDown="voteTrackDown" :user="user" class="hidden md:block" />
        </template>

        <li v-if="tracks.length === 0" class="flex items-center justify-center py-8 text-white/50 md:py-12" role="listitem">
          <div class="text-center">
            <Icon name="music" class="mx-auto mb-2 h-8 w-8 opacity-50 md:h-12 md:w-12" aria-hidden="true" />
            <p>{{ __('No tracks played yet') }}</p>
          </div>
        </li>
      </component>
    </div>
  </Card>
</template>
