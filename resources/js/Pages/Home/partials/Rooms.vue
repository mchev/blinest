<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'

defineProps({
  rooms: Object,
})
</script>
<template>
  <div class="flex flex-wrap">
    <Room v-for="room in rooms" :key="room.id" :room="room" />
  </div>
</template>
