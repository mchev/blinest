<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const track = ref(null)
const round = ref(null)
const playing = ref(props.room.is_playing)
const progress = ref(0)

const userCounter = ref(props.room.users_count)

const calculateProgression = () => {
  let current_track = round.value ? round.value.current : props.room.current_track_index
  progress.value = current_track / props.room.tracks_by_round * 100
}

watch(round, (value) => {
  calculateProgression()
})

onMounted(() => {
  if(props.room) {
    calculateProgression()
  }
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
  <Link :rel="(!room.is_public) ? 'nofollow': ''" :href="`/rooms/${room.slug}`" class="h-52 w-full">
    <article class="relative h-full w-full overflow-hidden">
      <header class="hidden">
        <h3>Blind Test {{ room.name }}</h3>
        <p>{{ room.description }}</p>
      </header>
      <div v-if="!room.is_public && room.owner" class="bg-purple-600 absolute truncate z-10 origin-top w-52 right-6 top-6 shadow-xl text-xs p-1 text-center" style="transform:translateX(50%) rotate(45deg);" >
        {{ room.owner.name }}
      </div>
      <figure class="h-40 w-full relative">
        <img :src="room.photo" :alt="'Illlustration de la room ' + room.name" class="object-cover w-full h-full rounded">
        <div class="absolute top-0 left-0 w-auto rounded-br-sm rounded-tl-sm bg-neutral-800 p-2 text-sm text-white">
          <div class="flex items-center">
            <span v-if="room.password" class="mr-1 font-bold text-orange-400">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" :title="__('Password protected')">
                <title>{{ __('Password protected') }}</title>
                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
              </svg>
            </span>
            <span v-if="!room.is_autostart" class="mr-1 font-bold text-orange-400">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" :title="__('This room is in manual start mode')">
                <title>{{ __('This room is in manual start mode') }}</title>
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
              </svg>
            </span>
            <Transition name="slide-fade">
              <svg v-if="playing" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-4 w-4 animate-pulse">
                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
              </svg>
            </Transition>
            <span class="font-semibold">{{ userCounter }}</span>
          </div>
        </div>
        <div v-if="progress" class="absolute flex gap-2 items-center bottom-0 w-full pt-8 pb-3 px-2 bg-gradient-to-b from-transparent to-neutral-900">
          <div class="w-full bg-neutral-800 rounded-full h-1">
            <div class="bg-neutral-100 h-1 rounded-full transition-all ease-in-out" :style="'width: '+progress+'%'"></div>
          </div>
          <div class="whitespace-nowrap text-sm">{{ round ? round.current : room.current_track_index }} / {{ room.tracks_by_round }}</div>
        </div>
      </figure>
      <div class="py-1">
        <p class="truncate text-gray-100" :title="room.name">{{ room.name }}</p>
        <p class="truncate text-sm text-neutral-500" :title="room.description">{{ room.description }}</p>
      </div>
    </article>
  </Link>
</template>
