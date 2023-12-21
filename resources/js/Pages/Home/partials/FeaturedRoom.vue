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
	<article>
		<Link :href="`/rooms/${room.slug}`">
			<figure class="relative mb-4 overflow-hidden rounded-2xl bg-shark-300 transition hover:scale-105">
				<img :src="room.photo" class="max-h-52 w-full object-cover" />
				<div class="absolute bottom-0 left-0 h-2 w-full rounded-full bg-shark-700 bg-opacity-50">
					<div class="h-2 rounded-full bg-red-500 transition-all ease-in-out" :style="'width: ' + progress + '%'"></div>
				</div>
			</figure>
		</Link>
		<h2 class="text-2xl">{{ room.name }}</h2>
		<p class="my-4">{{ room.description }}</p>
		<Link :href="`/rooms/${room.slug}`" class="flex items-center gap-2 bg-red-500 px-4 py-2 text-xl text-white hover:bg-red-400">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play-circle">
				<circle cx="12" cy="12" r="10" />
				<polygon points="10 8 16 12 10 16 10 8" />
			</svg>
			Rejoindre <span>{{ userCounter }} {{ __('player') }}{{ userCounter > 1 ? 's' : '' }}</span>
		</Link>
	</article>
</template>
