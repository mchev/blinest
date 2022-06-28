<script setup>
import { ref } from 'vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  channel: String,
  room: Object,
  round: [Object, Boolean],
})


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
  <Card>
    <div class="flex items-center gap-4">
      <button type="button" @click="stopRound">STOP ROUND</button>
      <button type="button" @click="startRound">START ROUND</button>
      <button type="button" @click="resumeTrack">PLAY</button>
      <button type="button" @click="pauseTrack">PAUSE</button>
      <button type="button" @click="playPreviousTrack">PREV</button>
      <button type="button" @click="playNextTrack">NEXT</button>
    </div>
  </Card>
</template>
