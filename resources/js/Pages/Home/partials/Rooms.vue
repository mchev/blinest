<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'
import Pagination from '@/Components/Pagination.vue'

defineProps({
  id: String | Number,
  rooms: Object,
})

const visit = (url) => {
  Inertia.visit(url, { preserveState: true, preserveScrol: true});
}

</script>
<template>
  
  <div class="grid md:grid-cols-4 xl:grid-cols-5 gap-4 relative">
    <button type="button" @click="visit(rooms.prev_page_url)" v-if="rooms.prev_page_url" class="absolute h-full flex items-center left-2 z-20">Previous</button>
    <button type="button" @click="visit(rooms.last_page_url)" v-else class="absolute h-full flex items-center left-2 z-20">Previous</button>
    <Room :room="room" v-for="room in rooms.data" :key="room.id" />
    <button type="button" @click="visit(rooms.next_page_url)" v-if="rooms.next_page_url" class="absolute right-2 z-20 h-full flex items-center">Next</button>
    <button type="button" @click="visit(rooms.first_page_url)" v-else class="absolute right-2 z-20 h-full flex items-center">Next</button>
  </div>
  
<!--   <Pagination :links="rooms.links"/>
 --></template>