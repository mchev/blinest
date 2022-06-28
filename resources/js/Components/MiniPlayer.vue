<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Spinner from '@/Components/Spinner.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
	track: Object,
})

const audio = new Audio()
const loading = ref(false)
const isPlaying = ref(false)

onUnmounted(() => {
	stop()
})

const play = () => {
	loading.value = true
	isPlaying.value = true
	audio.src = props.track.preview_url
	audio.addEventListener('canplaythrough', () => {
		loading.value = false
		audio.play()
	})
}

const stop = () => {
	isPlaying.value = false
	audio.pause()
}
</script>
<template>
	<Icon v-if="!isPlaying" name="play" class="mr-2 h-12 w-12 flex-shrink-0 cursor-pointer" @click="play" />
	<div v-else-if="loading" class="mr-2 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-neutral-800"><Spinner class="w-full h-full" /></div>
	<Icon v-else name="stop" class="mr-2 h-12 w-12 flex-shrink-0 cursor-pointer" @click="stop" />
</template>
