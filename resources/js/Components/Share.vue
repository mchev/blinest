<script setup>
import { ref, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Clip from '@/Components/Clip.vue'

defineProps({
	url: String,
})

const show = ref(false)
</script>
<template>
	<button @click="show = true" :class="$attrs.class">
		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
			<path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
		</svg>
	</button>

	<Modal :show="show" @close="show = false">
		<div class="p-6">
			<h2 class="my-4 text-xl">{{ __('Share this page') }}</h2>
			<Clip :text="url" />
		</div>
	</Modal>
</template>
