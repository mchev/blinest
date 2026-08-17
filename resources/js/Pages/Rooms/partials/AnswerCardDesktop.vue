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
  <li class="room-track-card" role="listitem">
    <!-- Desktop-optimized layout: horizontal with larger artwork -->
    <div class="flex flex-row gap-0">
      <!-- Large vinyl-style album artwork -->
      <div class="relative h-28 w-28 flex-shrink-0">
        <div class="absolute inset-0 z-0 m-2 rounded-full bg-black/50"></div>
        <div class="absolute inset-0 z-10 m-3 flex items-center justify-center overflow-hidden rounded-full bg-brand-deep">
          <div class="absolute inset-0 z-0 bg-gradient-to-br from-brand-midnight to-brand-deep opacity-50">
            <div class="bg-repeating-radial-gradient absolute inset-0 opacity-20"></div>
          </div>
          <div class="absolute inset-0 z-20 flex items-center justify-center">
            <div class="h-4 w-4 rounded-full border-2 border-white/20 bg-brand-midnight"></div>
          </div>
          <div class="absolute inset-0 z-10" :style="`transform: rotate(${Math.floor(Math.random() * 20) - 10}deg)`">
            <img :src="track.artwork_url" :alt="track.album_name" class="h-full w-full rounded-full object-cover" />
          </div>
          <div class="absolute left-1/4 top-0 z-20 h-3 w-12 -rotate-45 transform rounded-full bg-white opacity-20"></div>
          <div class="rotate-30 absolute bottom-1/4 right-1/3 z-20 h-2 w-8 transform rounded-full bg-white opacity-10"></div>
        </div>
      </div>

      <!-- Answers section: spacious layout -->
      <div class="min-w-0 flex-grow p-3">
        <div class="flex h-full flex-col justify-between">
          <div>
            <ul class="space-y-2" role="list">
              <li v-for="answer in track.answers" :key="answer.id" class="flex items-start gap-2 text-sm" role="listitem">
                <div class="flex min-w-0 flex-wrap items-start gap-2">
                  <div v-if="getUserAnswerForTrackAndAnswer(track, answer)" class="room-answer-badge flex-shrink-0" :class="{ 'mr-1': getUserAnswerForTrackAndAnswer(track, answer)?.order < 4 }">
                    <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.speedBonus" class="flex-shrink-0 text-brand-secondary" aria-label="Speed bonus">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                        <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    <span class="max-w-none truncate" :title="__(getUserAnswerForTrackAndAnswer(track, answer)?.name)">
                      {{ __(getUserAnswerForTrackAndAnswer(track, answer)?.name) }}
                    </span>
                    <span v-if="getUserAnswerForTrackAndAnswer(track, answer)?.order < 4" class="absolute -right-1.5 -top-1 flex h-4 w-4 items-center justify-center bg-brand-secondary text-[10px] font-bold text-brand-midnight">
                      {{ getUserAnswerForTrackAndAnswer(track, answer)?.order }}
                    </span>
                  </div>
                  <div v-else class="room-answer-badge--ghost flex-shrink-0">
                    <span class="max-w-none truncate" :title="__(answer.type.name)">
                      {{ __(answer.type.name) }}
                    </span>
                  </div>
                  <span class="min-w-0 flex-1 whitespace-normal font-medium leading-relaxed text-white/80">
                    {{ answer.value }}
                  </span>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Votes section: vertical layout -->
      <div class="flex flex-col items-center justify-between gap-1 p-3">
        <div class="flex flex-col items-center gap-2" v-if="user">
          <button @click="voteTrackUp(track)" class="group flex flex-col items-center gap-1 transition-all duration-200" :title="__('Upvote this track')" :aria-label="__('Upvote this track')" :aria-pressed="track.user_voted_up">
            <div class="flex h-7 w-7 items-center justify-center bg-brand-midnight transition-all duration-200 hover:bg-brand-accent" :class="{ 'bg-brand-accent': track.user_voted_up }">
              <Icon name="thumb-up" class="h-3.5 w-3.5 text-white" aria-hidden="true" />
            </div>
            <span class="min-w-[1.5rem] text-center text-xs font-medium text-white/60 transition-colors group-hover:text-brand-accent" :class="{ 'text-brand-accent': track.user_voted_up }">
              {{ track.upvotes }}
            </span>
          </button>

          <button @click="voteTrackDown(track)" class="group flex flex-col items-center gap-1 transition-all duration-200" :title="__('Downvote this track')" :aria-label="__('Downvote this track')" :aria-pressed="track.user_voted_down">
            <div class="flex h-7 w-7 items-center justify-center bg-brand-midnight transition-all duration-200 hover:bg-brand-primary" :class="{ 'bg-brand-primary': track.user_voted_down }">
              <Icon name="thumb-down" class="h-3.5 w-3.5 text-white" aria-hidden="true" />
            </div>
            <span class="min-w-[1.5rem] text-center text-xs font-medium text-white/60 transition-colors group-hover:text-brand-primary" :class="{ 'text-brand-primary-light': track.user_voted_down }">
              {{ track.downvotes }}
            </span>
          </button>
        </div>
      </div>
    </div>
  </li>
</template>
