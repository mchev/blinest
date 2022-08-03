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
	<div v-if="notification">
		<div class="flex justify-between items-center mb-1 border-b pb-1">
			<h4 class="uppercase text-red-500 flex items-center font-bold">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
				  <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
				</svg>
				{{ __('Alerte modération') }}
			</h4>
			<span class="text-xs text-neutral-400">{{ notification.data.created_at }}</span>
		</div>
		<div class="my-2 text-sm font-medium">
			{{ notification.data.message }}
		</div>
	</div>
</template>
