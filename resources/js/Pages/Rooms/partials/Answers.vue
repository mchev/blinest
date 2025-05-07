<script setup>
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { ref, watch, onMounted, onUnmounted, computed } from 'vue'
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

const progressPercentage = computed(() => {
  if (!round.value) return 0
  return Math.round((round.value.current / round.value.tracks.length) * 100)
})

onMounted(() => {
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
      let index = tracks.value.findIndex((x) => x.id === e.track.id)
      tracks.value[index].downvotes = e.track.downvotes
      tracks.value[index].upvotes = e.track.upvotes
    })
    .listen('NewScore', (e) => {
      if (e.score.user_id === user.id) {
        userAnswers.value.push(e.score)
      }
    })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})

const voteTrackDown = (track) => {
  axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/downvote`)
}

const voteTrackUp = (track) => {
  axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/upvote`)
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
        <li v-for="track in tracks" :key="track.id" class="rounded-lg bg-gradient-to-r from-neutral-800 to-neutral-900 shadow-md hover:shadow-lg transition-all duration-200 border border-neutral-700 overflow-hidden">
          <div class="flex">
            <div class="relative">
              <img :src="track.artwork_url" :alt="track.album_name" class="h-28 w-28 object-cover" />
              <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 px-2 py-1 text-xs text-white truncate">
                {{ track.album_name }}
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
                        <div v-else class="rounded-md bg-neutral-700 px-2 py-0.5 text-xs font-bold uppercase text-neutral-300 shadow-sm">
                          {{ __(answer.type.name) }}
                        </div>
                        <span class="font-medium text-neutral-200">{{ answer.value }}</span>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div class="flex flex-col items-end justify-between p-3 border-l border-neutral-700">
              <a v-if="track.track_url" 
                 class="flex items-center whitespace-nowrap text-xs text-neutral-400 hover:text-purple-400 transition-colors" 
                 :href="track.track_url" 
                 target="_blank" 
                 :title="__('Listen on') + ' ' + track.provider">
                {{ __('Listen on') }} 
                <Icon :name="track.provider" class="ml-1 h-5 w-5" />
              </a>
              
              <div class="flex items-center gap-4" v-if="user">
                <Tooltip>
                  <button @click="voteTrackUp(track)" 
                          class="flex items-center gap-1 text-neutral-400 hover:text-green-400 transition-colors">
                    <Icon name="thumb-up" class="h-5 w-5" />
                    <span class="text-xs font-medium">{{ track.upvotes }}</span>
                  </button>
                  <template #tooltip>
                    <div class="bg-neutral-800 px-2 py-1 rounded shadow-lg">{{ __('Like') }}</div>
                  </template>
                </Tooltip>
                
                <Tooltip>
                  <button @click="voteTrackDown(track)" 
                          class="flex items-center gap-1 text-neutral-400 hover:text-red-400 transition-colors">
                    <Icon name="thumb-down" class="h-5 w-5" />
                    <span class="text-xs font-medium">{{ track.downvotes }}</span>
                  </button>
                  <template #tooltip>
                    <div class="bg-neutral-800 px-2 py-1 rounded shadow-lg">{{ __('Don\'t like') }}</div>
                  </template>
                </Tooltip>
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
