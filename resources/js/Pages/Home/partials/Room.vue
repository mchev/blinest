<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const track = ref(null)
const round = ref(null)
const playing = ref(props.room.is_playing)

const userCounter = ref(0)

// Todo : better presence reactivity

onMounted(() => {
  Echo.channel(channel)
    .listen('RoundStarted', (e) => {
      userCounter.value = e.round.room.users_count
      playing.value = true
      round.value = e.round
    })
    .listen('TrackPlayed', (e) => {
      props.room.value = e.room
      userCounter.value = e.room.users_count
      round.value = e.round
      track.value = e.track
    })
    .listen('RoundFinished', (e) => {
      userCounter.value = e.round.room.users_count
      playing.value = false
      round.value = e.round
      round.value.current = 0
    })
})

onUnmounted(() => {
  Echo.leave(channel)
})
</script>
<template>
  <Link :href="`/rooms/${room.id}`" class="relative flex h-48 w-full flex-col items-center justify-center rounded-md bg-neutral-800 bg-cover bg-center shadow shadow grayscale-[30%] transition duration-100 ease-in-out hover:z-10 hover:scale-110 hover:grayscale-0" :style="`background-image: url(${room.photo_path || room.mosaic});`">
    <article class="relative h-full w-full">
      <div v-if="!room.is_public" class="ribbon truncate text-xs">@{{ room.owner.name }}</div>
      <div class="absolute top-0 left-0 w-auto rounded-br-md rounded-tl-md bg-purple-800/90 py-1 px-2 text-sm ease-in-out hover:scale-110" :title="__('Players')">
        <div class="flex items-center">
          <Transition name="slide-fade">
            <svg v-if="playing" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="mr-2 h-5 w-5 animate-[spin_3s_linear_infinite]">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </Transition>
          <span class="font-bold text-neutral-300">{{ userCounter }}</span>
        </div>
      </div>

      <div class="absolute bottom-0 flex w-full items-center justify-between rounded-b bg-neutral-800 p-2 text-sm uppercase text-gray-100">
        <span class="truncate font-bold">{{ room.name }}</span>
        <div class="flex items-center">{{ round ? round.current : 0 }} / {{ room.tracks_by_round }}</div>
      </div>
    </article>
  </Link>
</template>
