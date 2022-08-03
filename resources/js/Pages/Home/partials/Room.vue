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

// Todo : better presence reactivity

onMounted(() => {
  Echo.channel(channel)
  .listen('RoundStarted', (e) => {
    playing.value = true
    round.value = e.round
  })
  .listen('TrackPlayed', (e) => {
    props.room.value = e.room
    round.value = e.round
    track.value = e.track
  })
  .listen('RoundFinished', () => {
    playing.value = false
    round.value.current = 0
  })
})

onUnmounted(() => {
  Echo.leave(channel)
})
</script>
<template>
  <Link :href="`/rooms/${room.id}`" class="relative flex h-48 w-full flex-col items-center justify-center rounded bg-neutral-800 bg-cover bg-center shadow transition duration-100 ease-in-out hover:z-10 hover:scale-110 grayscale-[30%] hover:grayscale-0" :style="`background-image: url(${room.mosaic});`">
    <article class="relative h-full w-full">
      <div v-if="!room.is_public" class="ribbon truncate text-xs">@{{ room.owner.name }}</div>
      <div class="absolute ease-in-out top-0 left-0 pl-2 pr-4 py-2 w-auto text-sm bg-neutral-800/90 rounded-br-full hover:scale-110" :title="__('Players')">
        <div class="flex items-center">
          <Transition name="slide-fade">
            <svg v-if="playing" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-pulse mr-2" viewBox="0 0 20 20" fill="currentColor">
              <title>{{ __('Round in progress') }}</title>
              <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
            </svg>
          </Transition>

          {{ room.users_count }}
        </div>
      </div>

      <div class="absolute bottom-0 flex w-full items-center justify-between rounded-b bg-neutral-800 p-2 text-sm uppercase text-gray-100">
        <span class="truncate font-bold">{{ room.name }}</span>
        <div class="flex items-center">{{ round ? round.current : 0 }} / {{ room.tracks_by_round }}</div>
      </div>
    </article>
  </Link>
</template>
