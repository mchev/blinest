<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import PlayingIcon from '@/Components/PlayingIcon.vue'

const props = defineProps({
    room: {
        type: Object,
        required: true
    },
})

const user = usePage().props.auth.user

const channel = computed(() => `rooms.${props.room.id}`)
const track = ref(null)
const round = ref(null)
const playing = ref(props.room.is_playing)
const progress = ref(0)

const userCounter = ref(props.room.subscriptions)

const calculateProgression = () => {
    const current_track = round.value ? round.value.current : props.room.current_track_index
    progress.value = (current_track / props.room.tracks_by_round) * 100
}

watch(() => round.value, (newValue, oldValue) => {
    if (newValue !== oldValue) {
        calculateProgression()
    }
}, { deep: true })

onMounted(() => {
    if (props.room) {
        calculateProgression()
    }
    
    const echoChannel = Echo.channel(channel.value)
    
    echoChannel.listen('RoundStarted', (e) => {
        playing.value = true
        round.value = e.round
        progress.value = 0
    })
    .listen('TrackPlayed', (e) => {
        props.room.value = e.room
        round.value = e.round
        track.value = e.track
        progress.value = round.value.current === 1 ? 0 : ((round.value.current - 1) / props.room.tracks_by_round) * 100
    })
    .listen('RoundFinished', (e) => {
        playing.value = false
        round.value = e.round
        round.value.current = 0
        progress.value = 100
    })

    if (user) {
        Echo.private(`room.count.${props.room.id}`)
            .listenForWhisper('updatedUserCount', (e) => {
                userCounter.value = e.count
            })
    }
})

onUnmounted(() => {
    Echo.leave(channel.value)
    Echo.leave(`room.count.${props.room.id}`)
})
</script>
<template>
    <Link :rel="!room.is_public ? 'nofollow' : ''" :href="`/rooms/${room.slug}`" class="group w-full">
    <div class="flex flex-col items-center justify-center gap-4 rounded-[.6rem] border-2 border-red-500 p-[1rem]">
        <div class="w-full text-center">
            <h3 class="truncate font-semibold" :title="room.name"><span class="sr-only">Blind Test</span> {{ room.name }}</h3>
            <div v-if="!room.is_public && room.owner" class="text-xs flex items-center gap-1 justify-center">
                <img :src="room.owner.photo" :alt="room.owner.name" class="w-6 rounded-full" loading="lazy" />
                {{ room.owner.name }}
            </div>
        </div>
        <figure class="relative h-20 w-full overflow-hidden rounded-2xl group-hover:scale-105 transition bg-shark-300">
            <img :src="room.photo" :alt="'Illustration de la room ' + room.name" class="object-cover w-full h-full" loading="lazy" />
            <PlayingIcon v-if="playing" class="absolute w-full left-0 top-0" />
            <div class="absolute bottom-0 left-0 h-2 w-full rounded-full bg-shark-700 bg-opacity-50">
                <div class="h-2 rounded-full bg-red-500 transition-all ease-in-out" :style="{ width: `${progress}%` }"></div>
            </div>
        </figure>
        <div class="flex w-full items-center justify-between text-sm">
            <div class="inline-flex gap-1 items-center font-bold">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 22C2 17.5817 5.58172 14 10 14C14.4183 14 18 17.5817 18 22H16C16 18.6863 13.3137 16 10 16C6.68629 16 4 18.6863 4 22H2ZM10 13C6.685 13 4 10.315 4 7C4 3.685 6.685 1 10 1C13.315 1 16 3.685 16 7C16 10.315 13.315 13 10 13ZM10 11C12.21 11 14 9.21 14 7C14 4.79 12.21 3 10 3C7.79 3 6 4.79 6 7C6 9.21 7.79 11 10 11ZM18.2837 14.7028C21.0644 15.9561 23 18.752 23 22H21C21 19.564 19.5483 17.4671 17.4628 16.5271L18.2837 14.7028ZM17.5962 3.41321C19.5944 4.23703 21 6.20361 21 8.5C21 11.3702 18.8042 13.7252 16 13.9776V11.9646C17.6967 11.7222 19 10.264 19 8.5C19 7.11935 18.2016 5.92603 17.041 5.35635L17.5962 3.41321Z"></path></svg>
                {{ userCounter }} 
            </div>
            <span v-if="room.password" class="mr-1 font-bold text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" :aria-label="__('Password protected')">
                    <title>{{ __('Password protected') }}</title>
                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                </svg>
            </span>
            <span v-if="!room.is_autostart" class="mr-1 font-bold text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" :aria-label="__('This room is in manual start mode')">
                    <title>{{ __('This room is in manual start mode') }}</title>
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                </svg>
            </span>
            <div class="whitespace-nowrap">{{ round ? round.current : room.current_track_index }} / {{ room.tracks_by_round }}</div>
        </div>
    </div>
    </Link>
</template>