<script setup>
import { ref } from 'vue'
import Player from './Player.vue'
import UserInput from './UserInput.vue'

defineProps({
  room: {
    type: Object,
    required: true,
  },
  channel: {
    type: String,
    required: true,
  },
  initialTrack: {
    type: Object,
    default: null,
  },
  initialStartTime: {
    type: Number,
    default: 0,
  },
  initialRound: {
    type: Object,
    default: null,
  },
})

const currentTime = ref(0)
</script>

<template>
  <div class="min-w-0 space-y-2">
    <Player
      :room="room"
      :channel="channel"
      :initial-track="initialTrack"
      :initial-start-time="initialStartTime"
      @track:currentTime="currentTime = $event"
    />
    <UserInput
      :channel="channel"
      :current-time="currentTime"
      :room="room"
      :initial-track="initialTrack"
      :initial-round="initialRound"
    />
  </div>
</template>
