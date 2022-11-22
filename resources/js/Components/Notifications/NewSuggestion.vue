<script setup>
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
	notification: Object,
})

const emit = defineEmits(['markedAsdone'])

const markAsDone = () => {
	if (confirm('Ceci masquera aussi la suggestion pour les autres modérateurs.')) {
		emit('markedAsdone')
		Inertia.post(`/users/notifications/${props.notification.id}/done`, {
			preserveScroll: true,
		})
	}
}
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
		<span class="flex text-xs">Envoyée par {{ notification.data.user.name }} sur {{ notification.data.room.name }}</span>
		<span v-if="notification.data.room.playlists" class="text-xs">Playlists : {{ notification.data.room.playlists }}</span>
		<button class="btn-primary btn-sm mt-2 ml-auto" @click="markAsDone">{{ __('Done') }}</button>
	</div>
</template>
