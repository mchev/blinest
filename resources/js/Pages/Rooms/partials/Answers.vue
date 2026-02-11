<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref, watch, onMounted, computed } from 'vue'
import axios from 'axios'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import AnswerCardMobile from './AnswerCardMobile.vue'
import AnswerCardDesktop from './AnswerCardDesktop.vue'

const props = defineProps({
  users: Array,
  channel: String,
})

const user = usePage().props.auth.user
const userAnswers = ref([])
const round = ref(null)
const tracks = ref([])

const progressPercentage = computed(() => {
  if (!round.value) return 0
  return Math.round((round.value.current / round.value.tracks.length) * 100)
})

// Format track duration in MM:SS format
const formatDuration = (seconds) => {
  if (!seconds) return '0:00'
  const mins = Math.floor(seconds / 60)
  const secs = Math.floor(seconds % 60)
  return `${mins}:${secs.toString().padStart(2, '0')}`
}

// Add computed property for track count
const trackCount = computed(() => {
  return `${round.value?.current || 0} / ${round.value?.tracks?.length || 0}`
})

onMounted(() => {
  if (!props.channel) return

  Echo.join(props.channel)
    .listen('RoundStarted', (e) => {
      round.value = e.round
      tracks.value = []
      userAnswers.value = []
    })
    .listen('TrackPlayed', (e) => {
      round.value = e.round
    })
    .listen('TrackEnded', (e) => {
      tracks.value.unshift(e.track)
      round.value = e.round
    })
    .listen('TrackVoted', (e) => {
      const index = tracks.value.findIndex((x) => x.id === e.track.id)
      if (index !== -1) {
        tracks.value[index].downvotes = e.track.downvotes
        tracks.value[index].upvotes = e.track.upvotes
      }
    })
    .listen('NewScore', (e) => {
      if (e.score.user_id === user?.id) {
        userAnswers.value.push(e.score)
      }
    })
})

const voteTrackDown = (track) => {
  if (!round.value?.room?.id) return
  axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/downvote`)
    .catch(error => console.error('Error downvoting track:', error))
}

const voteTrackUp = (track) => {
  if (!round.value?.room?.id) return
  axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/upvote`)
    .catch(error => console.error('Error upvoting track:', error))
}

const getUserAnswerForTrackAndAnswer = (track, answer) => {
  return userAnswers.value
    .filter(x => x.track_id === track.id)
    .flatMap(x => x.answers)
    .find(a => a.id === answer.id)
}

</script>
<template>
  <Card class="rounded-xl shadow-lg border border-neutral-800">
    <template #header>
      <div class="flex w-full items-center justify-between flex-wrap gap-2">
        <h3 class="text-base md:text-xl font-bold flex items-center" role="heading" aria-level="2">
          <Icon name="music" class="mr-2 h-4 w-4 md:h-5 md:w-5 text-purple-400" aria-hidden="true" />
          {{ __('Playlist') }}
        </h3>
        <div v-if="round" class="flex flex-col items-end">
          <div class="text-xs md:text-sm font-medium text-neutral-400" aria-label="Track progress">
            {{ __('Track') }} {{ trackCount }}
          </div>
          <div class="w-20 md:w-32 bg-neutral-700 rounded-full h-1.5 md:h-2 mt-1" role="progressbar" :aria-valuenow="progressPercentage" aria-valuemin="0" aria-valuemax="100">
            <div class="bg-purple-500 h-1.5 md:h-2 rounded-full transition-all duration-300" :style="`width: ${progressPercentage}%`"></div>
          </div>
        </div>
      </div>
    </template>

    <div class="h-64 md:h-80 2xl:h-96 overflow-y-auto pr-2" 
         style="scrollbar-width: thin; scrollbar-color: rgba(128, 90, 213, 0.5) rgba(0, 0, 0, 0.1);"
         role="list"
         aria-label="Played tracks">
      <transition-group name="flip-list" tag="ul" class="space-y-3 md:space-y-4">
        <template v-for="track in tracks" :key="track.id">
          <!-- Mobile version -->
          <AnswerCardMobile
            :track="track"
            :getUserAnswerForTrackAndAnswer="getUserAnswerForTrackAndAnswer"
            :voteTrackUp="voteTrackUp"
            :voteTrackDown="voteTrackDown"
            :user="user"
            class="md:hidden"
          />
          
          <!-- Desktop version -->
          <AnswerCardDesktop
            :track="track"
            :getUserAnswerForTrackAndAnswer="getUserAnswerForTrackAndAnswer"
            :voteTrackUp="voteTrackUp"
            :voteTrackDown="voteTrackDown"
            :user="user"
            class="hidden md:block"
          />
        </template>
        
        <li v-if="tracks.length === 0" 
            class="flex items-center justify-center py-8 md:py-12 text-neutral-500"
            role="listitem">
          <div class="text-center">
            <Icon name="music" class="h-8 w-8 md:h-12 md:w-12 mx-auto mb-2 opacity-50" aria-hidden="true" />
            <p>{{ __('No tracks played yet') }}</p>
          </div>
        </li>
      </transition-group>
    </div>
  </Card>
</template>
