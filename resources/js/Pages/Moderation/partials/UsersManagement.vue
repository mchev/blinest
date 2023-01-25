<script setup>
import { ref, watch, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Card from '@/Components/Card.vue'
import BanForm from '@/Components/Moderation/BanForm.vue'
import debounce from 'lodash/debounce'

const search = ref('')
const searching = ref(false)
const banningUser = ref(false)
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

const userHasBeenBanned = () => {
  banningUser.value = false
  selectUser(selectedUser.value)
}
</script>
<template>
  <Card>
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
      <div class="mb-4 flex w-full justify-between">
        <div class="flex items-center gap-2">
          <img :src="selectedUser.photo" class="h-10 w-10 rounded-full" :alt="selectedUser.name" />
          {{ selectedUser.name }}
        </div>
        <span class="text-sm text-neutral-500">Inscription le {{ selectedUser.created_at }}</span>
      </div>
      <div class="mb-4 flex w-full gap-4">
        <div v-if="selectedUser.latest_messages" class="rounded border border-neutral-600 p-2 md:w-1/2">
          <h3 class="mb-2 uppercase">Derniers messages</h3>
          <ul class="flex flex-col text-xs">
            <li v-for="message in selectedUser.latest_messages" :key="message.id" class="mb-2 flex flex-col">
              <span class="text-neutral-500">{{ message.time }} sur {{ message.room.name }} : <span v-if="message.deleted_at" class="text-red-500">[Supprimé]</span> [{{ message.reports }} signalements]</span>
              <span class="break-word">{{ message.body }}</span>
            </li>
          </ul>
        </div>
        <div v-if="selectedUser.bans" class="rounded border border-neutral-600 p-2 md:w-1/2">
          <h3 class="mb-2 uppercase">Historique des bans</h3>
          <ul v-if="selectedUser.bans.length" class="my-2 flex flex-col">
            <li v-for="ban in selectedUser.bans" :key="ban.id" class="border-neutral-600-b border-neutral-600-neutral-600 mb-2 flex flex-col border border p-2">
              <span class="text-xs text-neutral-500">Banni par : {{ ban.banned_by }}</span>
              <span class="text-xs text-neutral-500">le : {{ ban.created_at }}</span>
              <span class="text-xs text-neutral-500">Raison : {{ ban.comment }}</span>
              <span class="text-xs text-neutral-500">Expire le : {{ ban.expired_at }}</span>
              <!--                   <button class="btn-secondary btn-sm mt-2">Annuler le ban</button>
		 -->
            </li>
          </ul>
          <div v-else class="text-sm text-neutral-500">Aucun</div>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button class="btn-secondary btn-sm" @click="selectedUser = null">Fermer</button>
        <button v-show="!banningUser" class="btn-danger btn-sm" @click="banningUser = true">Bannir</button>
      </div>
      <BanForm v-show="banningUser" :user="selectedUser" @userBanned="userHasBeenBanned" />
    </div>
  </Card>
</template>
