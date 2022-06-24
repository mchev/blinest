<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const infos = ref(null)

onMounted(() => {
  Echo.channel(channel).listen('TrackPlayed', (e) => {
    console.log(e.data)
    infos.value = e.data;
  })
})

onUnmounted(() => {
  Echo.leave(channel)
})
</script>
<template>
  <Link :href="`/rooms/${room.id}`" class="flex w-full flex-col relative items-center justify-center rounded-sm bg-gray-100 shadow dark:bg-gray-500 hover:scale-110 transition ease-in-out duration-100">
    <article class="relative overflow-hidden">
      <picture>
        <img :src="room.photo" :alt="room.name">
      </picture>
      <h3 class="font-bold">{{ room.name }}</h3>
      <div class="absolute top-1 right-2 w-auto rounded-sm font-bold">
        {{ infos ? infos.users_count : room.users_count }}
      </div>
      <span>Track {{ infos ? infos.current_track_index : room.current_track_index }} / {{ infos ? infos.tracks_count : room.tracks_by_game }}</span>
    </article>
  </Link>
</template>
