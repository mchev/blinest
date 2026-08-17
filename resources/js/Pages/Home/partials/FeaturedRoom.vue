<script setup>
import RoomCard from './RoomCard.vue'

defineProps({
  room: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <RoomCard :room="room" variant="featured" />
</template>
