<script setup>
import { defineEmits } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
	notification: Object,
})

const emit = defineEmits(['markedAsdone']);

const markAsDone = () => {
	if(confirm('Ceci masquera aussi la suggestion pour les autres modérateurs.')) {
		emit('markedAsdone');
		Inertia.post(`/users/notifications/${props.notification.id}/done`, {
			preserveScroll: true,
		})
	}
}

</script>
<template>
	<div v-if="notification" class="flex-grow">
		<div class="flex justify-between items-center mb-1 border-b pb-1">
			<h4 class="uppercase flex items-center font-bold">
				Suggestion
			</h4>
			<span class="text-xs text-neutral-400">{{ notification.data.created_at }}</span>
		</div>
		<div class="my-2 text-sm font-medium whitespace-pre-line">
			{{ notification.data.message }}
		</div>
		<span class="text-xs">Envoyée par {{ notification.data.user.name }} sur {{ notification.data.room.name }}</span>
		<button class="btn-primary btn-sm mt-2 ml-auto" @click="markAsDone">{{ __('Done') }}</button>
	</div>
</template>
