<script setup>
import Card from '@/Components/Card.vue'

const props = defineProps({
	stats: Object,
})
</script>
<template>
	<div class="mb-4 flex w-full flex-wrap justify-between gap-4 text-center">
		<Card class="flex-grow">
			<template #header>
				<h3 class="uppercase">Utilisateurs</h3>
			</template>
			{{ stats.users_count }}
		</Card>
		<Card class="flex-grow">
			<template #header>
				<h3 class="uppercase">Playlists publiques</h3>
			</template>
			{{ stats.public_playlists_count }}
		</Card>
		<Card class="flex-grow">
			<template #header>
				<h3 class="uppercase">Rooms publiques</h3>
			</template>
			{{ stats.public_rooms_count }}
		</Card>
		<Card class="flex-grow">
			<template #header>
				<h3 class="uppercase">Playlists</h3>
			</template>
			{{ stats.playlists_count }}
		</Card>
		<Card class="flex-grow">
			<template #header>
				<h3 class="uppercase">Rooms</h3>
			</template>
			{{ stats.rooms_count }}
		</Card>
		<Card class="flex-grow">
			<template #header>
				<h3 class="uppercase">Utilisateurs bannis</h3>
			</template>
			{{ stats.banned_users_count }}
		</Card>
	</div>
</template>
