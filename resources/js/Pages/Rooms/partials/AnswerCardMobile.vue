<script setup>
import { ref, computed } from 'vue'
import Icon from '@/Components/Icon.vue'
import TrackVoteActions from './TrackVoteActions.vue'

const props = defineProps({
  track: Object,
  getUserAnswerForTrackAndAnswer: Function,
  voteTrackUp: Function,
  voteTrackDown: Function,
  user: Object,
  roomId: Number,
  isLatest: {
    type: Boolean,
    default: false,
  },
})

const expanded = ref(props.isLatest)

const summaryLine = computed(() => {
  return (
    props.track.answers
      ?.map((answer) => answer.value)
      .filter(Boolean)
      .join(' · ') ?? ''
  )
})

const answerTypeInitial = (name) => {
  if (!name) {
    return '?'
  }

  return name.charAt(0).toUpperCase()
}

const toggleExpanded = () => {
  expanded.value = !expanded.value
}
</script>

<template>
  <li class="room-track-card room-track-card--mobile" role="listitem">
    <button type="button" class="room-track-card__toggle w-full text-left" :aria-expanded="expanded" @click="toggleExpanded">
      <div class="flex items-center gap-2 p-2">
        <div class="relative h-10 w-10 shrink-0">
          <div class="absolute inset-0 z-10 m-0.5 flex items-center justify-center overflow-hidden rounded-full bg-brand-deep">
            <img :src="track.artwork_url" :alt="track.album_name" class="h-full w-full rounded-full object-cover" />
          </div>
        </div>

        <div class="min-w-0 flex-1">
          <p class="truncate text-sm font-medium text-white/90">
            {{ summaryLine }}
          </p>
          <p v-if="!expanded" class="mt-0.5 truncate text-xs tracking-wide text-white/60">{{ track.answers?.length }} {{ __('Answers') }}</p>
        </div>

        <div v-if="user" class="shrink-0" @click.stop>
          <TrackVoteActions :track="track" :room-id="roomId" variant="mobile" />
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-white/60 transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </button>

    <div v-if="expanded" class="room-track-card__details border-t border-white/10 px-2 pb-2 pt-1.5">
      <ul class="space-y-1" role="list">
        <li v-for="answer in track.answers" :key="answer.id" class="flex min-w-0 items-center gap-1.5 text-sm" role="listitem">
          <span class="room-track-answer-type shrink-0" :class="getUserAnswerForTrackAndAnswer(track, answer) ? 'room-track-answer-type--found' : 'room-track-answer-type--ghost'" :title="__(answer.type?.name ?? answer.type_name)">
            {{ answerTypeInitial(__(answer.type?.name ?? answer.type_name)) }}
          </span>
          <span class="min-w-0 flex-1 truncate font-medium text-white/85">
            {{ answer.value }}
          </span>
        </li>
      </ul>
    </div>
  </li>
</template>
