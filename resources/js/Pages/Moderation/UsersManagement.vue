<script setup>
import { Head, router } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import { ref, watch } from 'vue'
import debounce from 'lodash/debounce'

const props = defineProps({
  users: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  user: {
    type: Object,
    default: null
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const search = ref(props.filters?.search || '')
const selectedUser = ref(props.user || null)
const showActionModal = ref(false)
const actionReason = ref('')
const actionDuration = ref(60) // 1 hour default
const actionUser = ref(null)
const formErrors = ref({})

watch(() => props.errors, (newErrors) => {
  if (newErrors.error) {
    formErrors.value = { error: newErrors.error }
  }
}, { immediate: true })

const performSearch = debounce(() => {
  selectedUser.value = null;
  router.get(
    route('moderation.users.index'),
    { search: search.value },
    { preserveState: true, preserveScroll: true }
  )
}, 300)

watch(search, () => {
  performSearch()
})

const viewUserDetails = (user) => {
  router.get(route('moderation.users.show', user.id))
}

const canBanUser = (user) => {
  return !user.is_admin && !user.is_moderator
}

const openBanModal = (user) => {
  actionUser.value = user
  actionReason.value = ''
  actionDuration.value = 60
  formErrors.value = {}
  showActionModal.value = true
}

const closeActionModal = () => {
  showActionModal.value = false
  actionUser.value = null
  actionReason.value = ''
  actionDuration.value = 60
  formErrors.value = {}
}

const performBan = () => {
  if (!actionReason.value) {
    formErrors.value = { reason: 'La raison est requise' }
    return
  }

  const data = {
    reason: actionReason.value,
    duration: actionDuration.value
  }

  router.post(
    route('moderation.users.ban', actionUser.value.id),
    data,
    {
      onSuccess: () => {
        closeActionModal()
      },
      onError: (errors) => {
        formErrors.value = errors
      }
    }
  )
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<template>
  <Layout title="Gestion des utilisateurs">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Barre de recherche -->
      <div class="mb-6">
        <div class="relative">
          <input
            v-model="search"
            type="text"
            placeholder="Rechercher un utilisateur..."
            class="w-full pl-10 pr-4 py-2 border border-gray-700 bg-black/30 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white placeholder-gray-400"
          />
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Liste des utilisateurs -->
      <div v-if="!selectedUser" class="bg-black/30 shadow rounded-lg overflow-hidden">
        <ul class="divide-y divide-gray-700">
          <li v-for="user in users.data" :key="user.id" class="p-4 hover:bg-black/40 cursor-pointer transition-colors duration-200" @click="viewUserDetails(user)">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center">
                      <span class="text-lg font-medium text-white">{{ user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                    <div v-if="user.is_banned" class="absolute -top-1 -right-1">
                      <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                      </svg>
                    </div>
                    <div v-if="user.is_admin || user.is_moderator" class="absolute -bottom-1 -right-1">
                      <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                      </svg>
                    </div>
                  </div>
                </div>
                <div>
                  <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-medium text-white">{{ user.name }}</h3>
                    <span v-if="user.is_admin" class="px-2 py-1 text-xs font-medium bg-blue-500/20 text-blue-400 rounded-full">Admin</span>
                    <span v-if="user.is_moderator" class="px-2 py-1 text-xs font-medium bg-purple-500/20 text-purple-400 rounded-full">Modérateur</span>
                  </div>
                  <p class="text-xs text-gray-500">Inscrit le {{ formatDate(user.created_at) }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <button
                  v-if="canBanUser(user)"
                  @click.stop="openBanModal(user)"
                  class="px-3 py-1 text-sm text-red-400 hover:text-red-300 transition-colors duration-200"
                >
                  Bannir
                </button>
                <button
                  v-else
                  disabled
                  class="px-3 py-1 text-sm text-gray-500 cursor-not-allowed"
                >
                  Bannissement impossible
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <!-- Détails de l'utilisateur -->
      <div v-if="selectedUser" class="bg-black/30 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <div class="flex items-center space-x-4">
            <div class="relative">
              <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center">
                <span class="text-2xl font-medium text-white">{{ selectedUser.name.charAt(0).toUpperCase() }}</span>
              </div>
              <div v-if="selectedUser.is_banned" class="absolute -top-1 -right-1">
                <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
              </div>
              <div v-if="selectedUser.is_admin || selectedUser.is_moderator" class="absolute -bottom-1 -right-1">
                <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
              </div>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <h2 class="text-2xl font-bold text-white">{{ selectedUser.name }}</h2>
                <span v-if="selectedUser.is_admin" class="px-2 py-1 text-sm font-medium bg-blue-500/20 text-blue-400 rounded-full">Admin</span>
                <span v-if="selectedUser.is_moderator" class="px-2 py-1 text-sm font-medium bg-purple-500/20 text-purple-400 rounded-full">Modérateur</span>
              </div>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button
              v-if="canBanUser(selectedUser)"
              @click="openBanModal(selectedUser)"
              class="px-4 py-2 text-sm font-medium text-red-400 hover:text-red-300 transition-colors duration-200"
            >
              Bannir
            </button>
            <button
              v-else
              disabled
              class="px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed"
            >
              Bannissement impossible
            </button>
            <button
              @click="selectedUser = null"
              class="text-gray-400 hover:text-gray-300 transition-colors duration-200"
            >
              Retour
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Informations -->
          <div class="bg-black/20 rounded-lg p-4">
            <h3 class="text-lg font-medium mb-2 text-white">Informations</h3>
            <div class="space-y-4">
              <div>
                <p class="text-sm text-gray-400">Inscrit le</p>
                <p class="text-white">{{ formatDate(selectedUser.created_at) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-400">Dernière connexion</p>
                <p class="text-white">{{ selectedUser.last_login_at ? formatDate(selectedUser.last_login_at) : 'Jamais' }}</p>
              </div>
            </div>
          </div>

          <!-- Statistiques -->
          <div class="bg-black/20 rounded-lg p-4">
            <h3 class="text-lg font-medium mb-2 text-white">Statistiques</h3>
            <div class="grid grid-cols-1 gap-4">
              <div class="text-center">
                <p class="text-2xl font-bold text-red-400">{{ selectedUser.bans?.length || 0 }}</p>
                <p class="text-sm text-gray-400">Bannissements</p>
              </div>
            </div>
          </div>

          <!-- Historique des bans -->
          <div class="bg-black/20 rounded-lg p-4 md:col-span-2">
            <h3 class="text-lg font-medium mb-2 text-white">Historique des bannissements</h3>
            <div class="space-y-4">
              <div v-if="selectedUser.bans?.length > 0" class="space-y-2">
                <div v-for="ban in selectedUser.bans" :key="ban.id" class="border-l-2 border-red-500 pl-4 py-2">
                  <p class="text-gray-300"><strong class="text-white">Raison :</strong> {{ ban.comment }}</p>
                  <p class="text-gray-300"><strong class="text-white">Date :</strong> {{ formatDate(ban.created_at) }}</p>
                  <p class="text-gray-300"><strong class="text-white">Date d'expiration :</strong> {{ ban.expires_at }}</p>
                  <p class="text-gray-300"><strong class="text-white">Par :</strong> {{ ban.banned_by }}</p>
                </div>
              </div>
              <p v-else class="text-gray-400">Aucun bannissement</p>
            </div>
          </div>

          <!-- Rooms -->
          <div class="bg-black/20 rounded-lg p-4">
            <h3 class="text-lg font-medium mb-2 text-white">Rooms</h3>
            <div v-if="selectedUser.rooms?.length > 0" class="space-y-4">
              <div v-for="room in selectedUser.rooms" :key="room.id" class="bg-black/30 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="text-white font-medium">{{ room.name }}</h4>
                    <p class="text-sm text-gray-400">Créée le {{ formatDate(room.created_at) }}</p>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 text-xs font-medium bg-blue-500/20 text-blue-400 rounded-full">
                      {{ room.messages_count }} messages
                    </span>
                    <span v-if="room.is_private" class="px-2 py-1 text-xs font-medium bg-purple-500/20 text-purple-400 rounded-full">
                      Privée
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <p v-else class="text-gray-400">Aucune room</p>
          </div>

          <!-- Playlists -->
          <div class="bg-black/20 rounded-lg p-4">
            <h3 class="text-lg font-medium mb-2 text-white">Playlists</h3>
            <div v-if="selectedUser.playlists?.length > 0" class="space-y-4">
              <div v-for="playlist in selectedUser.playlists" :key="playlist.id" class="bg-black/30 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="text-white font-medium">{{ playlist.name }}</h4>
                    <p class="text-sm text-gray-400">Créée le {{ formatDate(playlist.created_at) }}</p>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 text-xs font-medium bg-blue-500/20 text-blue-400 rounded-full">
                      {{ playlist.tracks_count }} titres
                    </span>
                    <span v-if="playlist.is_private" class="px-2 py-1 text-xs font-medium bg-purple-500/20 text-purple-400 rounded-full">
                      Privée
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <p v-else class="text-gray-400">Aucune playlist</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de bannissement -->
    <Transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="showActionModal" class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeActionModal"></div>

          <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div v-if="showActionModal" class="relative transform overflow-hidden rounded-lg bg-gray-900 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
              <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                <button
                  type="button"
                  class="rounded-md bg-gray-900 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  @click="closeActionModal"
                >
                  <span class="sr-only">Fermer</span>
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                  <h3 class="text-base font-semibold leading-6 text-white">
                    Bannir l'utilisateur
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-400">
                      Bannir l'utilisateur de la plateforme
                    </p>
                  </div>
                </div>
              </div>

              <div v-if="formErrors.error" class="mb-4 rounded-md bg-red-500/10 p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-red-400">{{ formErrors.error }}</p>
                  </div>
                </div>
              </div>

              <div class="mt-6 space-y-4">
                <div>
                  <label for="reason" class="block text-sm font-medium text-gray-300">Raison</label>
                  <textarea
                    id="reason"
                    v-model="actionReason"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-700 bg-black/30 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': formErrors.reason }"
                    placeholder="Entrez la raison du bannissement..."
                  />
                  <p v-if="formErrors.reason" class="mt-1 text-sm text-red-500">{{ formErrors.reason }}</p>
                </div>

                <div>
                  <label for="duration" class="block text-sm font-medium text-gray-300">Durée (en minutes)</label>
                  <input
                    type="number"
                    id="duration"
                    v-model="actionDuration"
                    min="1"
                    class="mt-1 block w-full rounded-md border-gray-700 bg-black/30 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    :class="{ 'border-red-500': formErrors.duration }"
                  />
                  <p v-if="formErrors.duration" class="mt-1 text-sm text-red-500">{{ formErrors.duration }}</p>
                  <p class="mt-1 text-sm text-gray-400">
                    Laissez vide pour un bannissement permanent
                  </p>
                </div>
              </div>

              <div class="mt-6 sm:mt-6 sm:flex sm:flex-row-reverse">
                <button
                  type="button"
                  class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                  @click="performBan"
                >
                  Confirmer
                </button>
                <button
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-700 hover:bg-gray-700 sm:mt-0 sm:w-auto"
                  @click="closeActionModal"
                >
                  Annuler
                </button>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </Transition>
  </Layout>
</template> 