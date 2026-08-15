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
  <li class="room-track-card"
      role="listitem">
    <!-- Mobile-optimized layout: vertical stack -->
    <div class="flex flex-col">
      <!-- Top row: Artwork and votes -->
      <div class="flex items-start justify-between p-2 gap-2">
        <!-- Compact vinyl artwork -->
        <div class="relative w-14 h-14 flex-shrink-0">
          <div class="absolute inset-0 rounded-full bg-black/50 m-1 z-0"></div>
          <div class="absolute inset-0 rounded-full bg-brand-deep m-1.5 z-10
                      flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-midnight to-brand-deep opacity-50 z-0"></div>
            <div class="absolute inset-0 flex items-center justify-center z-20">
              <div class="w-2 h-2 rounded-full bg-brand-midnight border-2 border-white/20"></div>
            </div>
            <img :src="track.artwork_url" 
                 :alt="track.album_name" 
                 class="h-full w-full object-cover rounded-full" />
            <div class="absolute top-0 left-1/4 w-6 h-1 bg-white opacity-20 rounded-full transform -rotate-45 z-20"></div>
          </div>
        </div>
        
        <!-- Votes on the right -->
        <div class="flex items-center gap-2" v-if="user">
          <button @click="voteTrackUp(track)" 
                  class="flex items-center gap-1"
                  :title="__('Upvote')"
                  :aria-label="__('Upvote this track')"
                  :aria-pressed="track.user_voted_up">
            <div class="flex items-center justify-center w-7 h-7 bg-brand-midnight transition-all"
                 :class="{ 'bg-brand-accent': track.user_voted_up }">
              <Icon name="thumb-up" class="h-3 w-3 text-white" aria-hidden="true" />
            </div>
            <span class="text-xs font-medium min-w-[1.5rem] text-center"
                  :class="track.user_voted_up ? 'text-brand-accent' : 'text-white/60'">
              {{ track.upvotes }}
            </span>
          </button>
          
          <button @click="voteTrackDown(track)" 
                  class="flex items-center gap-1"
                  :title="__('Downvote')"
                  :aria-label="__('Downvote this track')"
                  :aria-pressed="track.user_voted_down">
            <div class="flex items-center justify-center w-7 h-7 bg-brand-midnight transition-all"
                 :class="{ 'bg-brand-primary': track.user_voted_down }">
              <Icon name="thumb-down" class="h-3 w-3 text-white" aria-hidden="true" />
            </div>
            <span class="text-xs font-medium min-w-[1.5rem] text-center"
                  :class="track.user_voted_down ? 'text-brand-primary' : 'text-white/60'">
              {{ track.downvotes }}
            </span>
          </button>
        </div>
      </div>
      
      <!-- Answers section: compact vertical list -->
      <div class="px-2 pb-2">
        <ul class="space-y-1.5" role="list">
          <li v-for="answer in track.answers" 
              :key="answer.id" 
              class="flex items-start gap-1.5 text-xs"
              role="listitem">
            <div class="flex items-start gap-1.5 min-w-0 flex-1">
              <div v-if="getUserAnswerForTrackAndAnswer(track, answer)"
                   class="room-answer-badge flex-shrink-0"
                   :class="{ 'mr-1': getUserAnswerForTrackAndAnswer(track, answer)?.order < 4 }">
                <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.speedBonus"
                      class="text-brand-secondary flex-shrink-0"
                      aria-label="Speed bonus">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-2.5 w-2.5">
                    <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                  </svg>
                </span>
                <span class="truncate max-w-[60px]" :title="__(getUserAnswerForTrackAndAnswer(track, answer)?.name)">
                  {{ __(getUserAnswerForTrackAndAnswer(track, answer)?.name) }}
                </span>
                <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.order < 4"
                      class="absolute -right-1.5 -top-1 flex h-3 w-3 items-center justify-center bg-brand-secondary text-[8px] font-bold text-brand-midnight">
                  {{ getUserAnswerForTrackAndAnswer(track, answer)?.order }}
                </span>
              </div>
              <div v-else class="room-answer-badge--ghost flex-shrink-0">
                <span class="truncate max-w-[50px]" :title="__(answer.type.name)">
                  {{ __(answer.type.name) }}
                </span>
              </div>
              <span class="font-medium text-white/80 leading-relaxed flex-1 min-w-0 break-words">
                {{ answer.value }}
              </span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </li>
</template>

