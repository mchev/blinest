<script setup>
import { useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({
	notification: Object,
})

const form = useForm({
	notification_id: props.notification.id,
})

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
	</div>
</template>
