<script setup>
import { useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({
	notification: Object,
})

const form = useForm({
	notification_id: props.notification.id,
})

const decline = (team) => {
	form.post(`/teams/requests/${props.notification.data.teamRequest.id}/decline`)
}

const accept = (team) => {
	form.post(`/teams/requests/${props.notification.data.teamRequest.id}/accept`)
}
</script>
<template>
	<div v-if="notification" class="flex-grow">
		<h4 class="mb-1 border-b pb-1 uppercase text-neutral-500">{{ __('Team') }}</h4>
		<div class="my-2 text-sm font-medium">
			{{ notification.data.message }}
		</div>
		<div class="mt-1 flex items-center justify-end">
			<button @click="decline" class="btn-danger btn-sm mr-2 opacity-80">Decline</button>
			<button @click="accept" class="btn-primary btn-sm opacity-80">Accept</button>
		</div>
	</div>
</template>
