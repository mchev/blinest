<script setup>
import { ref } from 'vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  channel: String,
  room: Object,
  round: [Object, Boolean],
})

const isPlaying = true

const stopRound = () => {
  axios.post(`/rounds/${props.round.id}/stop`)
}
const startRound = () => {
  axios.post(`/rooms/${props.room.id}/start`)
}

const resumeTrack = () => {
  axios.post(`/rounds/${props.round.id}/track/resume`)
}
const pauseTrack = () => {
  axios.post(`/rounds/${props.round.id}/track/pause`)
}
const playPreviousTrack = () => {
  axios.post(`/rounds/${props.round.id}/track/prev`)
}
const playNextTrack = () => {
  axios.post(`/rounds/${props.round.id}/track/next`)
}
</script>
<template>
  <Card class="max-w-xl">
    <template #header>
      <h4 class="font-bold uppercase">Controle de la partie</h4>
    </template>
    <div class="flex flex-col gap-4">
      <div class="my-2 flex items-center justify-center rounded border-2 p-2">
        <button type="button" @click="playPreviousTrack">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
          </svg>
        </button>
        <button v-if="!isPlaying" type="button" @click="resumeTrack">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
        <button v-if="isPlaying" type="button" @click="pauseTrack">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
        <button type="button" @click="playNextTrack">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
          </svg>
        </button>
      </div>
      <div class="flex justify-center">
        <button v-if="round.is_playing" type="button" class="btn-danger" @click="stopRound">STOP ROUND</button>
        <button v-else type="button" class="btn-primary" @click="startRound">START ROUND</button>
      </div>

      <p class="flex items-start rounded bg-neutral-200 p-2 text-xs text-neutral-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
        <span>{{ __('To perform moderation operations on players it is possible to click on their name in the leaderboard or on a message in the chat.') }}</span>
      </p>
    </div>
  </Card>
</template>
