<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'

defineProps({
  rooms: Object,
})
</script>
<template>
  <ul class="flex w-full flex-wrap gap-3">
    <li v-for="room in rooms" :key="room.id" class="flex w-full md:w-1/3 lg:w-1/6">
      <Room :room="room" />
    </li>
  </ul>
</template>
