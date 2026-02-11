<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
	room: Object,
})

const user = usePage().props.auth.user
const cardRef = ref(null)

const publicChannelName = `room.public.${props.room.id}`
const track = ref(null)
const round = ref(null)
const playing = ref(props.room.is_playing)
const progress = ref(0)

const userCounter = ref(props.room.subscriptions)

const calculateProgression = () => {
	let current_track = round.value ? round.value.current : props.room.current_track_index
	progress.value = (current_track / props.room.tracks_by_round) * 100
}

let echoChannel = null
let observer = null

function subscribeEcho() {
	if (echoChannel) return
	echoChannel = Echo.channel(publicChannelName)
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
		Echo.leave(publicChannelName)
		echoChannel = null
	}
}

watch(round, (value) => {
	calculateProgression()
})

onMounted(() => {
	if (props.room) {
		calculateProgression()
	}
	if (!cardRef.value) return
	observer = new IntersectionObserver(
		(entries) => {
			if (entries[0].isIntersecting) {
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
	<article ref="cardRef">
		<Link :href="`/rooms/${room.slug}`">
			<figure class="relative mb-4 overflow-hidden rounded-2xl bg-shark-300 transition hover:scale-105">
				<img :src="room.photo" class="max-h-52 w-full object-cover" />
				<div class="absolute bottom-0 left-0 h-2 w-full rounded-full bg-shark-700 bg-opacity-50">
					<div class="h-2 rounded-full bg-red-500 transition-all ease-in-out" :style="'width: ' + progress + '%'"></div>
				</div>
			</figure>
		</Link>
		<p class="my-4">{{ room.description }}</p>
		<Link :href="`/rooms/${room.slug}`" class="flex items-center justify-center gap-2 rounded-lg bg-red-500 px-6 py-3 text-center font-medium text-white transition-all duration-300 hover:bg-red-400 hover:shadow-lg hover:shadow-red-500/20">
			{{ __('Play Now') }}
		</Link>
	</article>
</template>
