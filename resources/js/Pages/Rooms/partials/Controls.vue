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

</script>
<template>
  <Card>
    <div class="flex items-center gap-4">
      <button type="button" @click="stopRound">STOP</button>
      <button type="button" @click="newRound">PLAY</button>
      <button type="button" @click="pause">PAUSE</button>
      <button type="button" @click="playPreviousTrack">PREV</button>
      <button type="button" @click="playNextTrack">NEXT</button>
    </div>
  </Card>
</template>
