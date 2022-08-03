<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'
import SliderControls from '@/Components/SliderControls.vue'

defineProps({
  id: String | Number,
  rooms: Object,
})

</script>
<template>
  
  <div class="grid md:grid-cols-4 xl:grid-cols-5 gap-4 relative">
    <Room :room="room" v-for="room in rooms.data" :key="room.id" />
    <SliderControls :rooms="rooms"/>
  </div>
  
</template>