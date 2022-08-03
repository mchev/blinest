<script setup>
import { Inertia } from '@inertiajs/inertia'
import { ref, watch } from 'vue'

const props = defineProps({
	rooms: Object,
})

const prevUrl = ref(props.rooms.prev_page_url)
const nextUrl = ref(props.rooms.next_page_url || props.rooms.first_page_url)

const visit = (url) => {
	Inertia.visit(url, { preserveState: true, preserveScroll: true }, { only: ['rooms'] })
}

watch(
	() => props.rooms,
	(rooms) => {
		prevUrl.value = rooms.prev_page_url
		nextUrl.value = rooms.next_page_url || rooms.first_page_url
	},
	{ deep: true },
)
</script>
<template>
	<!-- Previous -->
	<button type="button" @click="visit(prevUrl)" v-if="prevUrl" class="slider-control scale-110" style="left: -1%" :title="__('Previous')">
		<svg xmlns="http://www.w3.org/2000/svg" class="icon h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
			<path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
		</svg>
	</button>
	<!-- Next -->
	<button type="button" @click="visit(nextUrl)" v-if="nextUrl && rooms.total > 5" class="slider-control scale-110" style="right: -1%" :title="__('Next')">
		<svg xmlns="http://www.w3.org/2000/svg" class="icon h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
			<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
		</svg>
	</button>
</template>

<style scoped>
.slider-control {
	bottom: 0;
	display: flex;
	height: 100%;
	justify-content: center;
	align-items: center;
	position: absolute;
	text-align: center;
	top: 0;
	width: 6%;
	z-index: 20;
	background: radial-gradient(hsla(0, 0%, 8%, 0.5), transparent);
}
.slider-control .icon {
	transform-origin: 45% 50%;
	transition: transform 0.1s ease-out 0s;
}
.slider-control:hover {
	background: radial-gradient(hsla(0, 0%, 8%, 0.7), transparent);
}
.slider-control:hover .icon {
	color: #fff;
	font-weight: 700;
	transform: scale(1.25);
}
</style>
