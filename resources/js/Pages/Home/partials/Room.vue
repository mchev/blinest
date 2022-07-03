<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const infos = ref(null)
const playing = ref(false)

// Todo : better presence reactivity

onMounted(() => {
  Echo.channel(channel).listen('TrackPlayed', (e) => {
    infos.value = e.data
    playing.value = true
  })
  Echo.channel(channel).listen('RoundFinished', () => {
    playing.value = false
    if (infos.value) infos.value.current_track_index = 0
  })
})

onUnmounted(() => {
  Echo.leave(channel)
})
</script>
<template>
  <Link :href="`/rooms/${room.id}`" class="relative flex w-full h-48 flex-col items-center justify-center rounded bg-neutral-800 bg-cover bg-center shadow transition duration-100 ease-in-out hover:z-10 hover:scale-110" :style="`background-image: url(${room.photo});`">
    <article class="h-full w-full">
      <div class="relative">
        <div v-if="!room.is_public" class="ribbon truncate text-xs">@{{ room.owner.name }}</div>
        <div class="absolute top-1 left-2 w-auto font-bold">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
            </svg>
            {{ infos ? infos.users_count : room.users_count }}
          </div>
        </div>
        <div class="absolute bottom-2 right-2 w-auto">
          <Transition name="slide-fade">
            <svg v-if="playing" xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4 animate-pulse" viewBox="0 0 20 20" fill="currentColor">
              <title>{{ __('Playing in progress') }}</title>
              <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
            </svg>
          </Transition>
        </div>
      </div>
      <div class="absolute bottom-0 rounded-b w-full flex items-center justify-between bg-gray-800 p-2 text-sm uppercase text-gray-100">
        {{ room.name }}
        <div class="flex items-center">{{ infos ? infos.current_track_index : room.current_track_index }} / {{ infos ? infos.tracks_count : room.tracks_by_round }}</div>
      </div>
    </article>
  </Link>
</template>
