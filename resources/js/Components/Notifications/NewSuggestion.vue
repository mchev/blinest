<script setup>

const props = defineProps({
	notification: Object,
})

const emit = defineEmits(['markedAsdone'])

</script>
<template>
	<div v-if="notification" class="flex-grow">
		<div class="mb-1 flex items-center justify-between border-b pb-1">
			<h4 class="flex items-center font-bold uppercase">Suggestion</h4>
			<span class="text-xs text-neutral-400">{{ notification.data.created_at }}</span>
		</div>
		<div class="my-2 whitespace-pre-line text-sm font-medium">
			{{ notification.data.message }}
		</div>
		<span class="flex text-xs">Envoyée par {{ notification.data.user.name }} sur <span class="font-bold ml-1">{{ notification.data.room.name }}</span></span>
		<span v-if="notification.data.room.playlists" class="text-xs">Playlists : {{ notification.data.room.playlists }}</span>
		<button type="button" class="btn-primary btn-sm mt-2 ml-auto" @click="$emit('markedAsdone')">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
				<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
			</svg>
			{{ __('Done') }}
		</button>
	</div>
</template>
