<script setup>
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  track: Object,
  getUserAnswerForTrackAndAnswer: Function,
  voteTrackUp: Function,
  voteTrackDown: Function,
  user: Object,
})
</script>

<template>
  <li class="rounded-lg bg-gradient-to-r from-black/20 to-black/40 shadow-md hover:shadow-lg transition-all duration-200 border border-black/50 overflow-hidden"
      role="listitem">
    <!-- Desktop-optimized layout: horizontal with larger artwork -->
    <div class="flex flex-row gap-0">
      <!-- Large vinyl-style album artwork -->
      <div class="relative w-28 h-28 flex-shrink-0">
        <div class="absolute inset-0 rounded-full bg-black/50 m-2 z-0"></div>
        <div class="absolute inset-0 rounded-full bg-neutral-800 m-3 z-10 
                    flex items-center justify-center overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-neutral-700 to-neutral-900 opacity-50 z-0">
            <div class="absolute inset-0 bg-repeating-radial-gradient opacity-20"></div>
          </div>
          <div class="absolute inset-0 flex items-center justify-center z-20">
            <div class="w-4 h-4 rounded-full bg-neutral-700 border-2 border-neutral-600"></div>
          </div>
          <div class="absolute inset-0 z-10" 
               :style="`transform: rotate(${Math.floor(Math.random() * 20) - 10}deg)`">
            <img :src="track.artwork_url" 
                 :alt="track.album_name" 
                 class="h-full w-full object-cover rounded-full" />
          </div>
          <div class="absolute top-0 left-1/4 w-12 h-3 bg-white opacity-20 rounded-full transform -rotate-45 z-20"></div>
          <div class="absolute bottom-1/4 right-1/3 w-8 h-2 bg-white opacity-10 rounded-full transform rotate-30 z-20"></div>
        </div>
      </div>
      
      <!-- Answers section: spacious layout -->
      <div class="flex-grow p-3 min-w-0">
        <div class="flex flex-col h-full justify-between">
          <div>
            <ul class="space-y-2" role="list">
              <li v-for="answer in track.answers" 
                  :key="answer.id" 
                  class="flex items-start text-sm gap-2"
                  role="listitem">
                <div class="flex items-start gap-2 min-w-0 flex-wrap">
                  <div v-if="getUserAnswerForTrackAndAnswer(track, answer)" 
                       class="relative flex items-center gap-1 rounded-md bg-gradient-to-r from-purple-600 to-purple-500 px-2 py-0.5 text-xs font-bold uppercase text-white shadow-sm flex-shrink-0 hover:from-purple-500 hover:to-purple-400 transition-colors" 
                       :class="{ 'mr-1': getUserAnswerForTrackAndAnswer(track, answer)?.order < 4 }">
                    <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.speedBonus" 
                          class="text-yellow-300 flex-shrink-0"
                          aria-label="Speed bonus">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                        <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    <span class="truncate max-w-none" :title="__(getUserAnswerForTrackAndAnswer(track, answer)?.name)">
                      {{ __(getUserAnswerForTrackAndAnswer(track, answer)?.name) }}
                    </span>
                    <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.order < 4" 
                          class="absolute -right-1.5 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-yellow-500 text-[10px] font-bold text-neutral-900 shadow">
                      {{ getUserAnswerForTrackAndAnswer(track, answer)?.order }}
                    </span>
                  </div>
                  <div v-else class="rounded-md bg-black/30 px-2 py-0.5 text-xs font-bold uppercase text-neutral-300 shadow-sm flex-shrink-0">
                    <span class="truncate max-w-none" :title="__(answer.type.name)">
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
      
      <!-- Votes section: vertical layout -->
      <div class="flex flex-col items-center justify-between p-3 gap-1">
        <div class="flex flex-col items-center gap-2" v-if="user">
          <button @click="voteTrackUp(track)" 
                  class="group flex flex-col items-center gap-1 transition-all duration-200"
                  :title="__('Upvote this track')"
                  :aria-label="__('Upvote this track')"
                  :aria-pressed="track.user_voted_up">
            <div class="flex items-center justify-center w-7 h-7 rounded-full bg-neutral-700/50 hover:bg-green-600 transition-all duration-200"
                 :class="{ 'bg-green-600': track.user_voted_up }">
              <Icon name="thumb-up" class="h-3.5 w-3.5 text-white" aria-hidden="true" />
            </div>
            <span class="text-xs font-medium text-neutral-400 group-hover:text-green-400 transition-colors min-w-[1.5rem] text-center"
                  :class="{ 'text-green-400': track.user_voted_up }">
              {{ track.upvotes }}
            </span>
          </button>
          
          <button @click="voteTrackDown(track)" 
                  class="group flex flex-col items-center gap-1 transition-all duration-200"
                  :title="__('Downvote this track')"
                  :aria-label="__('Downvote this track')"
                  :aria-pressed="track.user_voted_down">
            <div class="flex items-center justify-center w-7 h-7 rounded-full bg-neutral-700/50 hover:bg-red-600 transition-all duration-200"
                 :class="{ 'bg-red-600': track.user_voted_down }">
              <Icon name="thumb-down" class="h-3.5 w-3.5 text-white" aria-hidden="true" />
            </div>
            <span class="text-xs font-medium text-neutral-400 group-hover:text-red-400 transition-colors min-w-[1.5rem] text-center"
                  :class="{ 'text-red-400': track.user_voted_down }">
              {{ track.downvotes }}
            </span>
          </button>
        </div>
      </div>
    </div>
  </li>
</template>

