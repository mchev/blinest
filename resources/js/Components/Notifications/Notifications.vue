<script setup>
import { ref, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Dropdown from '@/Components/Dropdown.vue'
import NewTeamRequest from './NewTeamRequest.vue'
import TeamRequestApproved from './TeamRequestApproved.vue'
import TeamRequestRejected from './TeamRequestRejected.vue'
import NewRoomAlert from './NewRoomAlert.vue'
import NewSuggestion from './NewSuggestion.vue'

const user = usePage().props.value.auth.user

const notifications = ref(user.notifications)

onMounted(() => {
	Echo.private('App.Models.User.' + user.id).notification((notification) => {
		notifications.value.push(...[notification])
		console.log(notifications.value)
	})
})

const markAsRead = (notification) => {
	axios.post(`/users/notifications/${notification.id}/read`).then(() => {
		console.log('tesdd')
		notifications.value = notifications.value.filter(x => x.id !== notification.id)
	})
}
</script>
<template>
	<dropdown placement="bottom-end" :autoClose="false">
		<template #default>
			<div class="relative">
				<div v-if="notifications.length" class="absolute -top-2 -right-2 flex h-4 w-4 items-center justify-center truncate rounded-full bg-red-500 p-1 text-xs">
					{{ notifications.length }}
				</div>
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
				</svg>
			</div>
		</template>
		<template #dropdown>
			<div class="p-2 font-light">
				<ul v-if="notifications.length" class="max-w-xl max-h-96 overflow-y-scroll pr-2">
					<li v-for="notification in notifications" :key="notification.id" class="my-2 rounded bg-neutral-700 p-2 flex">
						<NewTeamRequest v-if="notification.type === 'App\\Notifications\\NewTeamRequest'" :notification="notification" />
						<TeamRequestApproved v-if="notification.type === 'App\\Notifications\\TeamRequestApproved'" :notification="notification" />
						<NewRoomAlert v-if="notification.type === 'App\\Notifications\\NewRoomAlert'" :notification="notification" />
						<NewSuggestion v-if="notification.type === 'App\\Notifications\\NewSuggestion'" :notification="notification" />
						<div class="justify-end">
							<button @click="markAsRead(notification)" class="pl-4 text-neutral-400">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
									<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
								</svg>
							</button>
						</div>
					</li>
				</ul>
				<span v-else>{{ __('No notification') }}</span>
			</div>
		</template>
	</dropdown>
</template>
