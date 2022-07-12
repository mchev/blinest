<script setup>
import { ref, watch, onMounted } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Card from '@/Components/Card.vue'
import debounce from 'lodash/debounce'

const props = defineProps({
  playlist: Object,
})

const search = ref('')
const searching = ref(false)
const users = ref(null)
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

const attach = (user) => {
  form
    .transform((data) => ({
      user_id: user.id,
    }))
    .post(`/playlists/${props.playlist.id}/moderators/attach`, {
      preserveScroll: true,
    })
}

const detach = (user) => {
  form
    .transform((data) => ({
      user_id: user.id,
    }))
    .delete(`/playlists/${props.playlist.id}/moderators/detach`, {
      preserveScroll: true,
    })
}
</script>
<template>
  <Card>
    <template #header>
      <h3 class="text-xl font-bold">{{ __('Moderators') }}</h3>
    </template>

    <dropdown placement="bottom-start" class="mb-2 pb-2" @closed="search = ''">
      <template #default>
        <text-input v-model="search" prepend-icon="search" append-icon="cheveron-down" :loading="searching" :placeholder="__('Add a moderator')" />
      </template>
      <template #dropdown>
        <ul v-if="users && users.data.length" class="max-w-50 max-h-80 overflow-y-auto">
          <li v-for="user in users.data" :key="user.id" class="flex items-center p-2">
            <img v-if="user.photo" class="-my-2 mr-2 block h-8 w-8 rounded-full" :src="user.photo" />
            <span class="mr-4">{{ user.name }}</span>
            <button class="ml-auto rounded-full bg-blue-500 py-1 px-2 text-xs uppercase" :title="__('Add')" @click="attach(user)">
              {{ __('Add') }}
            </button>
          </li>
        </ul>
      </template>
    </dropdown>

    <ul v-if="playlist.moderators.length">
      <li v-for="moderator in playlist.moderators" :key="moderator.id" class="flex items-center rounded p-3">
        <img v-if="moderator.photo" class="-my-2 mr-2 block h-8 w-8 rounded-full" :src="moderator.photo" />
        {{ moderator.name }}
        <button v-if="moderator.id != playlist.user_id" class="ml-auto text-red-500" :title="__('Remove')" @click="detach(moderator)">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
      </li>
    </ul>
    <p v-else class="my-2 text-sm text-neutral-400">{{ __('No moderator') }}</p>
  </Card>
</template>
