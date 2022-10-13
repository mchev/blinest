<script setup>
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
	notification: Object,
})

const read = (team) => {
	Inertia.post(`/users/notifications/${props.notification.id}/read`)
}

</script>
<template>
	<div v-if="notification" class="flex-grow">
		<h4 class="mb-1 border-b pb-1 uppercase text-neutral-500">{{ __('Team') }}</h4>
		<div class="my-2 text-sm font-medium">
			{{ notification.data.message }}
		</div>
		<div class="mt-1 flex items-center justify-end">
			<button @click="read" class="btn-primary btn-sm opacity-80">Ok</button>
		</div>
	</div>
</template>