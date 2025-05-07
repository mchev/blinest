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
      <div class="flex w-full items-center justify-between">
        <h3 class="text-xl font-bold flex items-center">
          <Icon name="music" class="mr-2 h-5 w-5 text-purple-400" />
          {{ __('Playlist') }}
        </h3>
        <div v-if="round" class="flex flex-col items-end">
          <div class="text-sm font-medium text-neutral-400">
            {{ __('Track') }} {{ round.current }} / {{ round.tracks.length }}
          </div>
          <div class="w-32 bg-neutral-700 rounded-full h-2 mt-1">
            <div class="bg-purple-500 h-2 rounded-full" :style="`width: ${progressPercentage}%`"></div>
          </div>
        </div>
      </div>
    </template>

    <div class="h-64 overflow-y-auto pr-2 md:h-80 2xl:h-96" style="scrollbar-width: thin; scrollbar-color: rgba(128, 90, 213, 0.5) rgba(0, 0, 0, 0.1);">
      <transition-group name="flip-list" tag="ul" class="space-y-4">
        <li v-for="track in tracks" 
            :key="track.id" 
            class="rounded-lg bg-gradient-to-r from-black/20 to-black/40 shadow-md hover:shadow-lg transition-all duration-200 border border-black/50 overflow-hidden"
            @mouseenter="setHovering(track.id, true)"
            @mouseleave="setHovering(track.id, false)">
          <div class="flex">
            <!-- Vinyl-style album artwork -->
            <div class="relative w-28 h-28 flex-shrink-0">
              <!-- Vinyl base -->
              <div class="absolute inset-0 rounded-full bg-black/50 m-2 z-0"></div>
              <!-- Vinyl record with grooves -->
              <div class="absolute inset-0 rounded-full bg-neutral-800 m-3 z-10 
                          flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-neutral-700 to-neutral-900 opacity-50 z-0">
                  <!-- Vinyl grooves simulation -->
                  <div class="absolute inset-0 bg-repeating-radial-gradient opacity-20"></div>
                </div>
                <!-- Center hole -->
                <div class="absolute inset-0 flex items-center justify-center z-20">
                  <div class="w-4 h-4 rounded-full bg-neutral-700 border-2 border-neutral-600"></div>
                </div>
                <!-- Album artwork with random rotation -->
                <div class="absolute inset-0 z-10" 
                     :style="`transform: rotate(${Math.floor(Math.random() * 20) - 10}deg)`">
                  <img :src="track.artwork_url" 
                       :alt="track.album_name" 
                       class="h-full w-full object-cover rounded-full" />
                </div>
                <!-- Light reflection effect -->
                <div class="absolute top-0 left-1/4 w-12 h-3 bg-white opacity-20 rounded-full transform -rotate-45 z-20"></div>
                <div class="absolute bottom-1/4 right-1/3 w-8 h-2 bg-white opacity-10 rounded-full transform rotate-30 z-20"></div>
              </div>
              <div v-if="track.duration" class="absolute top-0 right-0 bg-black bg-opacity-80 px-2 py-1 text-xs text-white rounded-tr-lg z-30">
                {{ formatDuration(track.duration) }}
              </div>
            </div>
            
            <div class="flex-grow p-3">
              <div class="flex flex-col h-full justify-between">
                <div>
                  <ul class="space-y-2">
                    <li v-for="answer in track.answers" :key="answer.id" class="flex items-start text-sm">
                      <div class="flex items-center gap-2">
                        <div v-if="getUserAnswerForTrackAndAnswer(track, answer)" 
                             class="relative flex items-center gap-1 rounded-md bg-gradient-to-r from-purple-600 to-purple-500 px-2 py-0.5 text-xs font-bold uppercase text-white shadow-sm" 
                             :class="{ 'mr-1': getUserAnswerForTrackAndAnswer(track, answer)?.order < 4 }">
                          <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.speedBonus" class="text-yellow-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                              <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                            </svg>
                          </span>
                          {{ __(getUserAnswerForTrackAndAnswer(track, answer)?.name) }}
                          <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.order < 4" 
                                class="absolute -right-2 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-yellow-500 text-[10px] font-bold text-neutral-900 shadow">
                            {{ getUserAnswerForTrackAndAnswer(track, answer)?.order }}
                          </span>
                        </div>
                        <div v-else class="rounded-md bg-black/30 px-2 py-0.5 text-xs font-bold uppercase text-neutral-300 shadow-sm">
                          {{ __(answer.type.name) }}
                        </div>
                        <span class="font-medium text-neutral-200">{{ answer.value }}</span>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div class="flex flex-col items-end justify-between p-3">
              <!-- <a v-if="track.track_url" 
                 class="flex items-center whitespace-nowrap text-xs font-medium px-3 py-1.5 rounded-full bg-neutral-700 hover:bg-purple-600 text-white transition-colors" 
                 :href="track.track_url" 
                 target="_blank" 
                 rel="noopener noreferrer"
                 :title="__('Listen on') + ' ' + track.provider">
                <Icon :name="track.provider" class="mr-1.5 h-4 w-4" />
                {{ __('Listen') }}
              </a> -->
              
              <div class="flex items-center gap-3 mt-2" v-if="user">
                <button @click="voteTrackUp(track)" 
                        class="group flex flex-col items-center gap-1 transition-all duration-200"
                        :title="__('Upvote this track')">
                  <div class="flex items-center justify-center w-10 h-10 rounded-full bg-neutral-700 hover:bg-green-600 transition-all duration-200"
                       :class="{ 'bg-green-600': track.user_voted_up }">
                    <Icon name="thumb-up" class="h-5 w-5 text-white" />
                  </div>
                  <span class="px-2 py-0.5 rounded-md bg-neutral-800 text-xs font-medium text-white group-hover:bg-green-700 transition-colors">
                    {{ track.upvotes }}
                  </span>
                </button>
                
                <button @click="voteTrackDown(track)" 
                        class="group flex flex-col items-center gap-1 transition-all duration-200"
                        :title="__('Downvote this track')">
                  <div class="flex items-center justify-center w-10 h-10 rounded-full bg-neutral-700 hover:bg-red-600 transition-all duration-200"
                       :class="{ 'bg-red-600': track.user_voted_down }">
                    <Icon name="thumb-down" class="h-5 w-5 text-white" />
                  </div>
                  <span class="px-2 py-0.5 rounded-md bg-neutral-800 text-xs font-medium text-white group-hover:bg-red-700 transition-colors">
                    {{ track.downvotes }}
                  </span>
                </button>
              </div>
            </div>
          </div>
        </li>
        
        <li v-if="tracks.length === 0" class="flex items-center justify-center py-8 text-neutral-500">
          <div class="text-center">
            <Icon name="music" class="h-12 w-12 mx-auto mb-2 opacity-50" />
            <p>{{ __('No tracks played yet') }}</p>
          </div>
        </li>
      </transition-group>
    </div>
  </Card>
</template>
