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
	<Modal :show="show">
		<div>
			<div class="flex items-center m-4">
				<Icon name="podium" class="mr-2 h-8 w-8" />
				<h2 class="uppercase font-bold text-teal-500">{{ room.name }}</h2>
			</div>
			<div v-if="loading">
				<Spinner />
			</div>
			<div v-else>
				<Card class="m-4">
					<template #header>
						<div class="flex items-center w-full justify-between">
						<h3 class="font-bold">{{ __('Scores for the last 7 days') }}</h3>
						<span class="font-bold rounded bg-teal-500 text-white text-sm p-1">{{ __('My score') }} : {{ scores.user.week.total }}<sup>PTS</sup></span>
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
								<td class="border-b p-2">{{ score.user.name }}</td>
								<td class="border-b p-2">{{ score.total }}</td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex items-center w-full justify-between">
						<h3 class="font-bold">{{ __('Scores for the last 30 days') }}</h3>
						<span class="font-bold rounded bg-teal-500 text-white text-sm p-1">{{ __('My score') }} : {{ scores.user.month.total }}<sup>PTS</sup></span>
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
								<td class="border-b p-2">{{ score.user.name }}</td>
								<td class="border-b p-2">{{ score.total }}</td>
							</tr>
						</tbody>
					</table>
				</Card>
				<Card class="m-4">
					<template #header>
						<div class="flex items-center w-full justify-between">
						<h3 class="font-bold">{{ __('All-time scores') }}</h3>
						<span class="font-bold rounded bg-teal-500 text-white text-sm p-1">{{ __('My score') }} : {{ scores.user.lifetime.total }}<sup>PTS</sup></span>
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
								<td class="border-b p-2">{{ score.user.name }}</td>
								<td class="border-b p-2">{{ score.total }}</td>
							</tr>
						</tbody>
					</table>
				</Card>
			</div>
		</div>
	</Modal>
</template>
