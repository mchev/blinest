<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
	room: Object,
})

const user = usePage().props.auth.user

const channel = `rooms.${props.room.id}`
const track = ref(null)
const round = ref(null)
const playing = ref(props.room.is_playing)
const progress = ref(0)

const userCounter = ref(props.room.subscriptions)

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
})

if (user) {
    Echo.private(`room.count.${props.room.id}`)
        .listenForWhisper('updatedUserCount', (e) => {
            userCounter.value = e.count
        })
}

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
		<p class="my-4">{{ room.description }}</p>
		<Link :href="`/rooms/${room.slug}`" class="flex rounded items-center gap-2 bg-red-500 px-4 py-2 font-bold text-white hover:bg-red-400">
			{{ __('Join') }} {{ room.name }}
		</Link>
	</article>
</template>
