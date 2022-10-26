<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'
import Top from './Top.vue'
import SliderControls from '@/Components/SliderControls.vue'

defineProps({
  id: String | Number,
  rooms: Object,
})

</script>
<template>
  
  <div v-if="rooms.data" class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-8 relative">
    <Room :room="room" v-for="room in rooms.data" :key="room.id" />
    <SliderControls :rooms="rooms"/>
  </div>
  <div v-else class="grid grid-cols-2 md:grid-cols-5 gap-8 relative">
    <Top :room="room" v-for="(room, index) in rooms" :key="room.id" :index="index" />
  </div>
  
</template>