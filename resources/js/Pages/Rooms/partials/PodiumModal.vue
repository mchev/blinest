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
			<div class="px-4 pt-2 flex items-center">
				<Icon name="podium" class="mr-2 h-8 w-8" />
				<h2 class="font-bold uppercase text-teal-500">{{ room.name }}</h2>
			</div>
			<div v-if="loading">
				<Spinner />
			</div>
			<div v-else class="grid grid-cols-1 xl:grid-cols-2">
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('All-time') }}</h3>
							<span class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.lifetime.total }}<sup>PTS</sup></span>
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
								<td class="border-b p-2">{{ score.total }}</td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('Teams') }}</h3>
							<span v-if="scores.user.team" class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.team.total }}<sup>PTS</sup></span>
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
								<td class="border-b p-2">{{ score.total }}</td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('Last 7 days') }}</h3>
							<span class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.week.total }}<sup>PTS</sup></span>
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
								<td class="border-b p-2">{{ score.total }}</td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex w-full items-center justify-between">
							<h3 class="font-bold">{{ __('Last 30 days') }}</h3>
							<span class="rounded bg-teal-500 p-1 font-bold text-white">{{ scores.user.month.total }}<sup>PTS</sup></span>
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
								<td class="border-b p-2">{{ score.total }}</td>
							</tr>
						</tbody>
					</table>
				</Card>
			</div>
		</div>
	</Modal>
</template>
