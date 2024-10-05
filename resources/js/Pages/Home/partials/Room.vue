<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import PlayingIcon from '@/Components/PlayingIcon.vue'

const props = defineProps({
    room: Object,
})

const channel = `rooms.${props.room.id}`
const track = ref(null)
const round = ref(null)
const playing = ref(props.room.is_playing)
const progress = ref(0)

const userCounter = ref(props.room.user_count)

const calculateProgression = () => {
    let current_track = round.value ? round.value.current : props.room.current_track_index
    progress.value = (current_track / props.room.tracks_by_round) * 100
}

watch(round, (value) => {
    calculateProgression()
})

onMounted(() => {
    if (props.room) {
        calculateProgression()
    }
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
        .listen('RoundFinished', (e) => {
            playing.value = false
            round.value = e.round
            round.value.current = 0
        })

    Echo.private(`room.count.${props.room.id}`)
        .listenForWhisper('updateUserCount', (e) => {
            userCounter.value = e.count
        })
})

onUnmounted(() => {
    Echo.leave(channel)
    Echo.leave('user-count')
})
</script>
<template>
    <Link :rel="!room.is_public ? 'nofollow' : ''" :href="`/rooms/${room.slug}`" class="group w-full">
    <div class="flex flex-col items-center justify-center gap-4 rounded-[.6rem] border-2 border-red-500 p-[1rem]">
        <div class="w-full text-center">
            <h3 class="truncate uppercase font-semibold" :title="room.name"><span class="hidden">Blind Test</span> {{ room.name }}</h3>
            <div v-if="!room.is_public && room.owner" class="text-xs flex items-center gap-1 justify-center">
                <img :src="room.owner.photo" :alt="room.owner.name" class="w-6 rounded-full" />
                {{ room.owner.name }}
            </div>
        </div>
        <figure class="relative h-20 w-full overflow-hidden rounded-2xl group-hover:scale-105 transition bg-shark-300">
            <img :src="room.photo" :alt="'Illlustration de la room ' + room.name" class="object-cover w-full h-full" />
            <PlayingIcon v-if="playing" class="absolute w-full left-0 top-0" />
            <div class="absolute bottom-0 left-0 h-2 w-full rounded-full bg-shark-700 bg-opacity-50">
                <div class="h-2 rounded-full bg-red-500 transition-all ease-in-out" :style="'width: ' + progress + '%'"></div>
            </div>
        </figure>
        <div class="flex w-full items-center justify-between text-sm">
            <span>{{ userCounter }} {{ __('player') }}{{userCounter > 1 ? 's' : ''}}</span>
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
            <div class="whitespace-nowrap">{{ round ? round.current : room.current_track_index }} / {{ room.tracks_by_round }}</div>
        </div>
    </div>
    </Link>
</template>