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

const userCounter = ref(props.room.users_count)

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
      <div class="absolute top-0 left-0 w-auto rounded-br-md rounded-tl-md bg-pink-500 py-1 px-2 text-sm ease-in-out hover:scale-110" :title="__('Players')">
        <div class="flex items-center">
          <span v-if="room.password" class="font-bold text-orange-500 mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" :title="__('Password protected')">
              <title>{{ __('Password protected') }}</title>
              <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
            </svg>
          </span>
          <Transition name="slide-fade">
            <svg v-if="playing" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-4 w-4 animate-pulse">
              <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
            </svg>
          </Transition>
          <span class="font-bold text-neutral-300">{{ userCounter }}</span>
        </div>
      </div>

      <div class="absolute bottom-0 flex w-full items-center justify-between rounded-b bg-neutral-800 p-2 text-sm uppercase text-gray-100">
        <span class="truncate font-bold">{{ room.name }}</span>
        <div class="flex items-center">{{ round ? round.current : room.current_track_index }} / {{ room.tracks_by_round }}</div>
      </div>
    </article>
  </Link>
</template>
