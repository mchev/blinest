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
const cardRef = ref(null)

const publicChannelName = computed(() => `room.public.${props.room.id}`)
const track = ref(null)
const round = ref(null)
const playing = ref(props.room.is_playing)
const progress = ref(0)

const userCounter = ref(props.room.subscriptions)

const calculateProgression = () => {
    const current_track = round.value?.current ?? props.room.current_track_index
    progress.value = (current_track / props.room.tracks_by_round) * 100
}

// Computed properties for better readability
const currentTrackDisplay = computed(() =>
    round.value ? round.value.current : props.room.current_track_index
)

const isPasswordProtected = computed(() => !!props.room.password)
const isManualStart = computed(() => !props.room.is_autostart)
const isPrivateRoom = computed(() => !props.room.is_public)

let echoChannel = null
let observer = null

function subscribeEcho() {
    if (echoChannel) return
    echoChannel = Echo.channel(publicChannelName.value)
    echoChannel.listen('RoomPublicState', (e) => {
        userCounter.value = e.memberCount ?? userCounter.value
        playing.value = e.isPlaying ?? playing.value
        const idx = e.currentTrackIndex ?? 0
        const total = e.tracksByRound || props.room.tracks_by_round || 1
        round.value = { current: idx }
        progress.value = total ? (idx / total) * 100 : 0
    })
}

function leaveEcho() {
    if (echoChannel) {
        Echo.leave(publicChannelName.value)
        echoChannel = null
    }
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
    if (!cardRef.value) return
    observer = new IntersectionObserver(
        (entries) => {
            const entry = entries[0]
            if (entry.isIntersecting) {
                subscribeEcho()
            } else {
                leaveEcho()
            }
        },
        { rootMargin: '100px', threshold: 0 }
    )
    observer.observe(cardRef.value)
})

onUnmounted(() => {
    if (observer && cardRef.value) {
        observer.disconnect()
    }
    leaveEcho()
})
</script>
<template>
    <div ref="cardRef" class="w-full">
        <Link :rel="isPrivateRoom ? 'nofollow' : ''" :href="`/rooms/${room.slug}`" class="group w-full block">
        <div class="overflow-hidden rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-slate-700 hover:border-red-500">
            <!-- Room Header -->
            <div class="relative">
                <figure class="relative h-32 w-full overflow-hidden">
                    <img :src="room.photo" :alt="'Illustration de la room ' + room.name" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent opacity-60"></div>
                    
                    <!-- Playing indicator overlay -->
                    <PlayingIcon v-if="playing" class="absolute w-full left-0 top-0 animate-pulse" />
                    
                    <!-- Status badges -->
                    <div class="absolute top-2 right-2 flex gap-2">
                        <span v-if="isPasswordProtected" class="flex items-center justify-center rounded-full bg-orange-500 p-1.5 shadow-lg" title="Password protected">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 text-white">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span v-if="isManualStart" class="flex items-center justify-center rounded-full bg-orange-500 p-1.5 shadow-lg" title="Manual start mode">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 text-white">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </figure>
                
                <!-- Progress bar -->
                <div class="absolute bottom-0 left-0 h-1.5 w-full bg-slate-800 bg-opacity-70">
                    <div class="h-full bg-gradient-to-r from-red-500 to-red-400 transition-all duration-500 ease-out" :style="{ width: `${progress}%` }"></div>
                </div>
            </div>
            
            <!-- Room Content -->
            <div class="p-4">
                <h3 class="truncate text-lg font-bold text-white group-hover:text-red-400 transition-colors" :title="room.name">
                    {{ room.name }}
                </h3>
                
                <!-- Owner info -->
                <div v-if="isPrivateRoom && room.owner" class="mt-1 flex items-center gap-2">
                    <img :src="room.owner.photo" :alt="room.owner.name" class="w-5 h-5 rounded-full ring-1 ring-slate-600" loading="lazy" />
                    <span class="text-xs text-slate-400">{{ room.owner.name }}</span>
                </div>
                
                <!-- Room stats -->
                <div class="mt-3 flex items-center justify-between text-sm">
                    <div class="flex items-center gap-1.5 rounded-full bg-slate-700 px-2.5 py-1 text-slate-300">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M2 22C2 17.5817 5.58172 14 10 14C14.4183 14 18 17.5817 18 22H16C16 18.6863 13.3137 16 10 16C6.68629 16 4 18.6863 4 22H2ZM10 13C6.685 13 4 10.315 4 7C4 3.685 6.685 1 10 1C13.315 1 16 3.685 16 7C16 10.315 13.315 13 10 13ZM10 11C12.21 11 14 9.21 14 7C14 4.79 12.21 3 10 3C7.79 3 6 4.79 6 7C6 9.21 7.79 11 10 11ZM18.2837 14.7028C21.0644 15.9561 23 18.752 23 22H21C21 19.564 19.5483 17.4671 17.4628 16.5271L18.2837 14.7028ZM17.5962 3.41321C19.5944 4.23703 21 6.20361 21 8.5C21 11.3702 18.8042 13.7252 16 13.9776V11.9646C17.6967 11.7222 19 10.264 19 8.5C19 7.11935 18.2016 5.92603 17.041 5.35635L17.5962 3.41321Z"></path>
                        </svg>
                        <span class="font-medium">{{ userCounter }}</span>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <span class="inline-flex items-center rounded-full bg-slate-700 px-2.5 py-1 font-medium text-slate-300">
                            <span v-if="playing" class="mr-1.5 h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                            {{ currentTrackDisplay }} / {{ room.tracks_by_round }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        </Link>
    </div>
</template>