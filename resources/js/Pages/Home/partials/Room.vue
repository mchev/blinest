<script setup>
import RoomCard from './RoomCard.vue'

defineProps({
  room: {
    type: Object,
    required: true,
  },
  variant: {
    type: String,
    default: 'default',
  },
})
</script>

<template>
  <div class="h-full">
    <RoomCard :room="room" :variant="variant" />
  </div>
</template>
