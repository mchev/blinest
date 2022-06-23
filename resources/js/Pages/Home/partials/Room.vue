<script setup>
import { ref, onMounted } from 'vue'
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
</script>
<template>
  <Link :href="`/rooms/${room.id}`" class="mr-4 flex flex-col h-32 w-32 items-center justify-center rounded-lg bg-gray-100 p-8 shadow dark:bg-gray-500">
    {{ room.name }}
    <span v-if="infos">Users : {{ infos.users_count}}</span>
    <span v-if="infos">Track {{ infos.current_track_index }} / {{ infos.tracks_count }}</span>
  </Link>
</template>
