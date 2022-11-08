<script setup>
import { ref, watch, onMounted } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Card from '@/Components/Card.vue'
import debounce from 'lodash/debounce'

const search = ref('')
const searching = ref(false)
const users = ref(null)
const selectedUser = ref(null)
const form = useForm({
	user_id: null,
})

watch(
	search,
	debounce(() => {
		searching.value = true
		axios.get('/api/users', { params: { search: search.value } }).then((response) => {
			users.value = response.data.users
			searching.value = false
		})
	}, 300),
)

const selectUser = (user) => {
	selectedUser.value = user
	axios.get(route('moderation.users.informations', user)).then((response) => {
		selectedUser.value = response.data
	})
}

const banningUser = (user) => {
	alert('Bientôt disponible')
}
</script>
<template>
	<Card>
		<template #header>
			<h3 class="font-bold">Gestion des utilisateurs</h3>
		</template>

		<dropdown placement="bottom-start" class="mb-2 pb-2" @closed="search = ''">
			<template #default>
				<text-input v-model="search" prepend-icon="search" append-icon="cheveron-down" :loading="searching" placeholder="Chercher un utilisateur" />
			</template>
			<template #dropdown>
				<ul v-if="users && users.data.length" class="max-w-50 max-h-80 overflow-y-auto">
					<li v-for="user in users.data" :key="user.id" class="flex items-center p-2">
						<img v-if="user.photo" class="-my-2 mr-2 block h-8 w-8 rounded-full" :src="user.photo" />
						<span class="mr-4">{{ user.name }}</span>
						<button class="ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase" @click="selectUser(user)">Sélectionner</button>
					</li>
				</ul>
			</template>
		</dropdown>

		<div v-if="selectedUser">
			<div class="flex w-full justify-between mb-4">
				<div class="flex items-center gap-2">
					<img :src="selectedUser.photo" class="h-10 w-10 rounded-full" :alt="selectedUser.name" />
					{{ selectedUser.name }}
				</div>
				<span class="text-sm text-neutral-500">Inscription le {{ selectedUser.created_at }}</span>
			</div>
			<div class="mb-4 flex w-full gap-4">
				<div class="rounded border p-2 md:w-1/2">
					<h3>Derniers messages</h3>
					<ul class="flex flex-col text-xs">
						<li v-for="message in selectedUser.latest_messages" :key="message.id" class="flex items-center gap-2">
							<span class="break-all"
								><span class="italic">{{ message.time }}:</span> {{ message.body }}</span
							>
						</li>
					</ul>
				</div>
				<div class="rounded border p-2 md:w-1/2">
					<h3>Bans</h3>
					<ul class="my-2 flex flex-col">
						<li v-for="ban in selectedUser.bans" :key="ban.id" class="flex flex-col border-b border-neutral-600 p-2">
							<span class="text-xs text-neutral-500">Banni par : {{ ban.banned_by }}</span>
							<span class="text-xs text-neutral-500">le : {{ ban.created_at }}</span>
							<span class="text-xs text-neutral-500">Raison : {{ ban.comment }}</span>
							<span class="text-xs text-neutral-500">Expire le : {{ ban.expired_at }}</span>
							<!--                   <button class="btn-secondary btn-sm mt-2">Annuler le ban</button>
		 -->
						</li>
					</ul>
				</div>
			</div>
			<div class="flex items-center gap-2">
				<button class="btn-secondary btn-sm" @click="selectedUser = null">Fermer</button>
				<button class="btn-danger btn-sm" @click="banningUser(user)">Bannir</button>
			</div>
		</div>
	</Card>
</template>
