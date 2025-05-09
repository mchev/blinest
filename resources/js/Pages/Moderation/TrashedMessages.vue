<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import debounce from 'lodash/debounce'
import { watch } from 'vue'

const props = defineProps({
  trashedMessages: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({
      search: '',
      per_page: 10,
      sort_by: 'created_at',
      sort_direction: 'desc'
    })
  }
})

const search = ref(props.filters?.search || '')
const selectedRoom = ref('all')
const selectedUser = ref('all')
const perPage = ref(props.filters?.per_page || 10)
const sortBy = ref(props.filters?.sort_by || 'created_at')
const sortDirection = ref(props.filters?.sort_direction || 'desc')

const filteredMessages = computed(() => {
  return props.trashedMessages.data.filter(message => {
    const matchesSearch = message.body.toLowerCase().includes(search.value.toLowerCase()) ||
                         message.user.name.toLowerCase().includes(search.value.toLowerCase())
    const matchesRoom = selectedRoom.value === 'all' || message.room.id === selectedRoom.value
    const matchesUser = selectedUser.value === 'all' || message.user.id === selectedUser.value
    return matchesSearch && matchesRoom && matchesUser
  })
})

const rooms = computed(() => {
  const uniqueRooms = new Map()
  props.trashedMessages.data.forEach(message => {
    if (!uniqueRooms.has(message.room.id)) {
      uniqueRooms.set(message.room.id, message.room)
    }
  })
  return Array.from(uniqueRooms.values())
})

const users = computed(() => {
  const uniqueUsers = new Map()
  props.trashedMessages.data.forEach(message => {
    if (!uniqueUsers.has(message.user.id)) {
      uniqueUsers.set(message.user.id, message.user)
    }
  })
  return Array.from(uniqueUsers.values())
})

const performSearch = debounce(() => {
  router.get(
    route('moderation.trashed-messages'),
    {
      search: search.value,
      per_page: perPage.value,
      sort_by: sortBy.value,
      sort_direction: sortDirection.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  )
}, 300)

watch([search, perPage, sortBy, sortDirection], () => {
  performSearch()
})

const restoreMessage = (id) => {
  if (confirm('Êtes-vous sûr de vouloir restaurer ce message ?')) {
    router.post(route('moderation.trashed-messages.restore', id))
  }
}

const permanentlyDeleteMessage = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer définitivement ce message ? Cette action ne peut pas être annulée.')) {
    router.delete(route('moderation.trashed-messages.destroy', id))
  }
}
</script>

<template>
  <Layout title="Messages supprimés">
    <div class="space-y-6">
      <!-- Search and Filters -->
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1">
          <div class="relative rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-neutral-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
              </svg>
            </div>
            <input
              type="text"
              v-model="search"
              class="block w-full rounded-md border-0 bg-black/20 py-1.5 pl-10 text-white shadow-sm ring-1 ring-inset ring-neutral-600 placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-neutral-500 sm:text-sm sm:leading-6"
              placeholder="Rechercher des messages..."
            />
          </div>
        </div>
        <div class="flex items-center gap-4">
          <select
            v-model="perPage"
            class="rounded-md border-0 bg-black/20 py-1.5 pl-3 pr-10 text-white shadow-sm ring-1 ring-inset ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-neutral-500 sm:text-sm sm:leading-6"
          >
            <option value="10">10 par page</option>
            <option value="25">25 par page</option>
            <option value="50">50 par page</option>
            <option value="100">100 par page</option>
          </select>
          <select
            v-model="sortBy"
            class="rounded-md border-0 bg-black/20 py-1.5 pl-3 pr-10 text-white shadow-sm ring-1 ring-inset ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-neutral-500 sm:text-sm sm:leading-6"
          >
            <option value="created_at">Date</option>
            <option value="user_id">Utilisateur</option>
            <option value="room_id">Salle</option>
          </select>
          <button
            @click="sortDirection = sortDirection === 'asc' ? 'desc' : 'asc'"
            class="rounded-md bg-black/20 p-2 text-neutral-400 hover:text-white"
          >
            <svg v-if="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Messages List -->
      <div class="overflow-hidden rounded-lg bg-black/20 backdrop-blur-sm shadow">
        <ul role="list" class="divide-y divide-neutral-800">
          <li v-for="message in filteredMessages" :key="message.id" class="p-4 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                  <p class="text-sm font-medium text-white truncate">
                    {{ message.user.name }}
                  </p>
                  <span class="text-xs text-neutral-400">
                    dans {{ message.room.name }}
                  </span>
                </div>
                <p class="mt-1 text-sm text-neutral-300">
                  {{ message.body }}
                </p>
                <div class="mt-2 flex items-center gap-4 text-xs text-neutral-400">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Supprimé le {{ message.deleted_at }}
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Créé le {{ message.created_at }}
                  </span>
                </div>
              </div>
              <div class="ml-4 flex items-center gap-2">
                <button
                  @click="restoreMessage(message.id)"
                  class="rounded-md bg-green-500/10 p-2 text-green-400 hover:bg-green-500/20"
                  title="Restaurer le message"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                  </svg>
                </button>
                <button
                  @click="permanentlyDeleteMessage(message.id)"
                  class="rounded-md bg-red-500/10 p-2 text-red-400 hover:bg-red-500/20"
                  title="Supprimer définitivement"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- Pagination -->
      <div class="flex items-center justify-between border-t border-neutral-800 bg-black/20 backdrop-blur-sm px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
          <Link
            v-if="trashedMessages.prev_page_url"
            :href="trashedMessages.prev_page_url"
            class="relative inline-flex items-center rounded-md bg-black/20 px-4 py-2 text-sm font-medium text-white hover:bg-black/30"
          >
            Précédent
          </Link>
          <Link
            v-if="trashedMessages.next_page_url"
            :href="trashedMessages.next_page_url"
            class="relative ml-3 inline-flex items-center rounded-md bg-black/20 px-4 py-2 text-sm font-medium text-white hover:bg-black/30"
          >
            Suivant
          </Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-neutral-400">
              Affichage de
              <span class="font-medium">{{ trashedMessages.from || 0 }}</span>
              à
              <span class="font-medium">{{ trashedMessages.to || 0 }}</span>
              sur
              <span class="font-medium">{{ trashedMessages.total || 0 }}</span>
              résultats
            </p>
          </div>
          <div>
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
              <template v-for="link in trashedMessages.links" :key="link.label">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  v-html="link.label"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-medium"
                  :class="[
                    link.active
                      ? 'z-10 bg-neutral-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-600'
                      : 'text-neutral-400 hover:bg-black/30 hover:text-white',
                    link.label.includes('Previous') || link.label.includes('Next')
                      ? 'rounded-md'
                      : '',
                  ]"
                />
                <span
                  v-else
                  v-html="link.label"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400"
                  :class="[
                    link.active
                      ? 'z-10 bg-neutral-600 text-white'
                      : 'text-neutral-400',
                    link.label.includes('Previous') || link.label.includes('Next')
                      ? 'rounded-md'
                      : '',
                  ]"
                />
              </template>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template> 