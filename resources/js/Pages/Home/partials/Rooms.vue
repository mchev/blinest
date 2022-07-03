<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

defineProps({
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
  <Carousel :settings="settings" :breakpoints="breakpoints">
    <Slide v-for="room in rooms" :key="room.id">
      <Room :room="room" />
    </Slide>
    <template #addons>
      <Navigation />
<!--       <Pagination />
 -->    </template>
  </Carousel>
</template>

<style scoped>
  
  .carousel__slide {
    margin: 1.5rem;
  }

</style>