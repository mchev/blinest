<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref, watch, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Tooltip from '@/Components/Tooltip.vue'

const props = defineProps({
  users: Array,
  channel: String,
})

const user = usePage().props.auth.user
const userAnswers = ref([])
const round = ref(null)
const tracks = ref([])
const isHovering = ref({})

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

  Echo.channel(props.channel)
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

onUnmounted(() => {
  if (props.channel) {
    Echo.leave(props.channel)
  }
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

const setHovering = (trackId, isHover) => {
  isHovering.value = { ...isHovering.value, [trackId]: isHover }
}
</script>
<template>
  <Card class="rounded-xl shadow-lg border border-neutral-800">
    <template #header>
      <div class="flex w-full items-center justify-between flex-wrap gap-2">
        <h3 class="text-lg sm:text-xl font-bold flex items-center" role="heading" aria-level="2">
          <Icon name="music" class="mr-2 h-5 w-5 text-purple-400" aria-hidden="true" />
          {{ __('Playlist') }}
        </h3>
        <div v-if="round" class="flex flex-col items-end">
          <div class="text-sm font-medium text-neutral-400" aria-label="Track progress">
            {{ __('Track') }} {{ trackCount }}
          </div>
          <div class="w-24 sm:w-32 bg-neutral-700 rounded-full h-2 mt-1" role="progressbar" :aria-valuenow="progressPercentage" aria-valuemin="0" aria-valuemax="100">
            <div class="bg-purple-500 h-2 rounded-full" :style="`width: ${progressPercentage}%`"></div>
          </div>
        </div>
      </div>
    </template>

    <div class="h-48 sm:h-64 md:h-80 2xl:h-96 overflow-y-auto pr-2" 
         style="scrollbar-width: thin; scrollbar-color: rgba(128, 90, 213, 0.5) rgba(0, 0, 0, 0.1);"
         role="list"
         aria-label="Played tracks">
      <transition-group name="flip-list" tag="ul" class="space-y-2 sm:space-y-4">
        <li v-for="track in tracks" 
            :key="track.id" 
            class="rounded-lg bg-gradient-to-r from-black/20 to-black/40 shadow-md hover:shadow-lg transition-all duration-200 border border-black/50 overflow-hidden"
            role="listitem">
          <div class="grid grid-cols-[auto_1fr_auto] sm:flex sm:flex-row gap-2 sm:gap-0">
            <!-- Vinyl-style album artwork -->
            <div class="relative w-16 h-16 sm:w-28 sm:h-28 flex-shrink-0">
              <!-- Vinyl base -->
              <div class="absolute inset-0 rounded-full bg-black/50 m-1 sm:m-2 z-0"></div>
              <!-- Vinyl record with grooves -->
              <div class="absolute inset-0 rounded-full bg-neutral-800 m-1.5 sm:m-3 z-10 
                          flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-neutral-700 to-neutral-900 opacity-50 z-0">
                  <div class="absolute inset-0 bg-repeating-radial-gradient opacity-20"></div>
                </div>
                <!-- Center hole -->
                <div class="absolute inset-0 flex items-center justify-center z-20">
                  <div class="w-2 h-2 sm:w-4 sm:h-4 rounded-full bg-neutral-700 border-2 border-neutral-600"></div>
                </div>
                <!-- Album artwork -->
                <div class="absolute inset-0 z-10" 
                     :style="`transform: rotate(${Math.floor(Math.random() * 20) - 10}deg)`">
                  <img :src="track.artwork_url" 
                       :alt="track.album_name" 
                       class="h-full w-full object-cover rounded-full" />
                </div>
                <!-- Light reflection effect -->
                <div class="absolute top-0 left-1/4 w-6 sm:w-12 h-1.5 sm:h-3 bg-white opacity-20 rounded-full transform -rotate-45 z-20"></div>
                <div class="absolute bottom-1/4 right-1/3 w-4 sm:w-8 h-1 sm:h-2 bg-white opacity-10 rounded-full transform rotate-30 z-20"></div>
              </div>
            </div>
            
            <div class="flex-grow p-2 sm:p-3 min-w-0">
              <div class="flex flex-col h-full justify-between">
                <div>
                  <ul class="space-y-1 sm:space-y-2" role="list">
                    <li v-for="answer in track.answers" 
                        :key="answer.id" 
                        class="flex items-start text-xs sm:text-sm gap-1 sm:gap-2"
                        role="listitem">
                      <div class="flex items-start gap-1 sm:gap-2 min-w-0 flex-wrap">
                        <div v-if="getUserAnswerForTrackAndAnswer(track, answer)" 
                             class="relative flex items-center gap-1 rounded-md bg-gradient-to-r from-purple-600 to-purple-500 px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-bold uppercase text-white shadow-sm flex-shrink-0" 
                             :class="{ 'mr-1': getUserAnswerForTrackAndAnswer(track, answer)?.order < 4 }">
                          <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.speedBonus" 
                                class="text-yellow-300 flex-shrink-0"
                                aria-label="Speed bonus">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-2.5 w-2.5 sm:h-3 sm:w-3">
                              <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                            </svg>
                          </span>
                          <span class="truncate max-w-[80px] sm:max-w-none" :title="__(getUserAnswerForTrackAndAnswer(track, answer)?.name)">
                            {{ __(getUserAnswerForTrackAndAnswer(track, answer)?.name) }}
                          </span>
                          <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.order < 4" 
                                class="absolute -right-1.5 -top-1 flex h-3 w-3 sm:h-4 sm:w-4 items-center justify-center rounded-full bg-yellow-500 text-[8px] sm:text-[10px] font-bold text-neutral-900 shadow">
                            {{ getUserAnswerForTrackAndAnswer(track, answer)?.order }}
                          </span>
                        </div>
                        <div v-else class="rounded-md bg-black/30 px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-bold uppercase text-neutral-300 shadow-sm flex-shrink-0">
                          <span class="truncate max-w-[60px] sm:max-w-none" :title="__(answer.type.name)">
                            {{ __(answer.type.name) }}
                          </span>
                        </div>
                        <span class="font-medium text-neutral-200 whitespace-normal leading-relaxed flex-1 min-w-0">
                          {{ answer.value }}
                        </span>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div class="flex flex-row sm:flex-col items-center justify-between p-2 sm:p-3 gap-2 sm:gap-1">
              <div class="flex items-center gap-1.5 sm:gap-2" v-if="user">
                <button @click="voteTrackUp(track)" 
                        class="group flex items-center gap-1 transition-all duration-200"
                        :title="__('Upvote this track')"
                        :aria-label="__('Upvote this track')"
                        :aria-pressed="track.user_voted_up">
                  <div class="flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-neutral-700/50 hover:bg-green-600 transition-all duration-200"
                       :class="{ 'bg-green-600': track.user_voted_up }">
                    <Icon name="thumb-up" class="h-3 w-3 sm:h-3.5 sm:w-3.5 text-white" aria-hidden="true" />
                  </div>
                  <span class="text-[10px] sm:text-xs font-medium text-neutral-400 group-hover:text-green-400 transition-colors min-w-[1.5rem] text-center"
                        :class="{ 'text-green-400': track.user_voted_up }">
                    {{ track.upvotes }}
                  </span>
                </button>
                
                <button @click="voteTrackDown(track)" 
                        class="group flex items-center gap-1 transition-all duration-200"
                        :title="__('Downvote this track')"
                        :aria-label="__('Downvote this track')"
                        :aria-pressed="track.user_voted_down">
                  <div class="flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-neutral-700/50 hover:bg-red-600 transition-all duration-200"
                       :class="{ 'bg-red-600': track.user_voted_down }">
                    <Icon name="thumb-down" class="h-3 w-3 sm:h-3.5 sm:w-3.5 text-white" aria-hidden="true" />
                  </div>
                  <span class="text-[10px] sm:text-xs font-medium text-neutral-400 group-hover:text-red-400 transition-colors min-w-[1.5rem] text-center"
                        :class="{ 'text-red-400': track.user_voted_down }">
                    {{ track.downvotes }}
                  </span>
                </button>
              </div>
            </div>
          </div>
        </li>
        
        <li v-if="tracks.length === 0" 
            class="flex items-center justify-center py-6 sm:py-8 text-neutral-500"
            role="listitem">
          <div class="text-center">
            <Icon name="music" class="h-8 w-8 sm:h-12 sm:w-12 mx-auto mb-2 opacity-50" aria-hidden="true" />
            <p>{{ __('No tracks played yet') }}</p>
          </div>
        </li>
      </transition-group>
    </div>
  </Card>
</template>
