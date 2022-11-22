<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Spinner from '@/Components/Spinner.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
	room: Object,
	show: Boolean,
})

const loading = ref(true)
const scores = ref(null)

onMounted(() => {
	axios.get(`/rooms/${props.room.id}/scores`).then((response) => {
		loading.value = false
		scores.value = response.data
	})
})
</script>
<template>
	<Modal :show="show" maxWidth="5xl">
		<div class="text-sm bg-neutral-800">
			<div class="px-4 pt-2 flex items-center justify-between">
				<h2 class="font-bold uppercase text-teal-500">{{ room.name }}</h2>
				<button @click="$emit('close')" :title="__('Close')" class="hover:text-white hover:animate-[spin_.5s_ease-in-out]">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
					  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>
			</div>
			<div v-if="loading" class="flex w-full p-12 items-center justify-center">
				<Spinner />
			</div>
			<div v-else class="grid grid-cols-1 xl:grid-cols-2">
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('All-time') }}</h3>
							<span class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.lifetime.score }}<sup class="ml-1">PTS</sup></span>
						</div>
					</template>
					<table class="w-full">
						<thead>
							<tr>
								<th class="border-b-2 p-2 text-left">#</th>
								<th class="border-b-2 p-2 text-left">{{ __('Name') }}</th>
								<th class="border-b-2 p-2 text-left">{{ __('Score') }}</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(score, index) in scores.lifetime">
								<td class="border-b p-2">{{ index + 1 }}</td>
								<td class="truncate border-b p-2">{{ score.user.name }}</td>
								<td class="border-b p-2">{{ score.score }}<sup class="ml-1">PTS</sup></td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('Teams') }}</h3>
							<span v-if="scores.user.team" class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.team.total }}<sup class="ml-1">PTS</sup></span>
						</div>
					</template>
					<table class="w-full">
						<thead>
							<tr>
								<th class="border-b-2 p-2 text-left">#</th>
								<th class="border-b-2 p-2 text-left">{{ __('Name') }}</th>
								<th class="border-b-2 p-2 text-left">{{ __('Score') }}</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(score, index) in scores.teams">
								<td class="border-b p-2">{{ index + 1 }}</td>
								<td class="truncate border-b p-2">{{ score.team.name }}</td>
								<td class="border-b p-2">{{ score.score }}<sup class="ml-1">PTS</sup></td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('Last 7 days') }}</h3>
							<span class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.week.total }}<sup class="ml-1">PTS</sup></span>
						</div>
					</template>
					<table class="w-full">
						<thead>
							<tr>
								<th class="border-b-2 p-2 text-left">#</th>
								<th class="border-b-2 p-2 text-left">{{ __('Name') }}</th>
								<th class="border-b-2 p-2 text-left">{{ __('Score') }}</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(score, index) in scores.week">
								<td class="border-b p-2">{{ index + 1 }}</td>
								<td class="truncate border-b p-2">{{ score.user.name }}</td>
								<td class="border-b p-2">{{ score.total }}<sup class="ml-1">PTS</sup></td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('Last 30 days') }}</h3>
							<span class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.month.total }}<sup class="ml-1">PTS</sup></span>
						</div>
					</template>
					<table class="w-full">
						<thead>
							<tr>
								<th class="border-b-2 p-2 text-left">#</th>
								<th class="border-b-2 p-2 text-left">{{ __('Name') }}</th>
								<th class="border-b-2 p-2 text-left">{{ __('Score') }}</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(score, index) in scores.month">
								<td class="border-b p-2">{{ index + 1 }}</td>
								<td class="truncate border-b p-2">{{ score.user.name }}</td>
								<td class="border-b p-2">{{ score.total }}<sup class="ml-1">PTS</sup></td>
							</tr>
						</tbody>
					</table>
				</Card>
			</div>
		</div>
	</Modal>
</template>
