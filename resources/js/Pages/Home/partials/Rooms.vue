<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'

defineProps({
  id: String | Number,
  rooms: Object,
})

const settings = {
  itemsToShow: 1,
  snapAlign: 'center',
}

const breakpoints = {
  700: {
    itemsToShow: 3.5,
    snapAlign: 'center',
  },
  1024: {
    itemsToShow: 5.5,
    snapAlign: 'start',
  },
}
</script>
<template>
  <div class="grid md:grid-cols-4 xl:grid-cols-5 gap-4">
    <Room :room="room" v-for="room in rooms" :key="room.id" />
  </div>
</template>

<style scoped>
.carousel__slide {
  margin: 1.5rem;
}
</style>
