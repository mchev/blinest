<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
	notification: Object,
})

const read = (team) => {
	router.post(`/users/notifications/${props.notification.id}/read`)
}

</script>
<template>
	<div v-if="notification" class="flex-grow">
		<h4 class="mb-1 border-b pb-1 uppercase text-neutral-500">{{ __('Team') }} {{ notification.data.team.name }}</h4>
		<div class="my-2 text-sm font-medium">
			{{ __('Ta demande a été acceptée!') }}<br>
		</div>
		<div class="mt-1 flex items-center justify-end">
			<button type="button" @click="read" class="btn-primary btn-sm opacity-80">{{ __('Mark as read') }}</button>
		</div>
	</div>
</template>