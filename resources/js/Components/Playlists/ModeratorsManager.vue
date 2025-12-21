<script setup>
import { ref, watch, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import TextInput from '@/Components/TextInput.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import debounce from 'lodash/debounce'

const props = defineProps({
  playlist: Object,
})

const page = usePage()

// Translation function for script setup
const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}

const search = ref('')
const searching = ref(false)
const users = ref(null)
const form = useForm({
  user_id: null,
})

const showDeleteModal = ref(false)
const moderatorToDelete = ref(null)

const isOwner = computed(() => {
  return props.playlist.moderators.some(m => m.id === props.playlist.user_id)
})

watch(
  search,
  debounce(() => {
    if (search.value.length < 2) {
      users.value = null
      return
    }
    searching.value = true
    axios.get('/api/users', { params: { search: search.value } }).then((response) => {
      users.value = response.data.users
      searching.value = false
    }).catch(() => {
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
      onSuccess: () => {
        search.value = ''
        users.value = null
      }
    })
}

const detach = (user) => {
  moderatorToDelete.value = user
  showDeleteModal.value = true
}

const confirmDetach = () => {
  if (!moderatorToDelete.value) return
  
  form
    .transform((data) => ({
      user_id: moderatorToDelete.value.id,
    }))
    .delete(`/playlists/${props.playlist.id}/moderators/detach`, {
      preserveScroll: true,
      onSuccess: () => {
        moderatorToDelete.value = null
      }
    })
}

const isModerator = (userId) => {
  return props.playlist.moderators.some(m => m.id === userId)
}
</script>
<template>
  <Card class="w-full">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-bold text-neutral-200">{{ __('Moderators') }}</h3>
          <p class="text-sm text-neutral-200 mt-0.5">{{ __('Moderators can edit tracks and manage the playlist') }}</p>
        </div>
      </div>
    </template>

    <div class="p-4 lg:p-5 space-y-4">
      <!-- Add Moderator Section -->
      <div>
        <label class="block text-sm font-medium text-neutral-200 mb-2">{{ __('Add a moderator') }}</label>
        <Dropdown placement="bottom-start" class="w-full" @closed="search = ''">
          <template #default>
            <TextInput 
              v-model="search" 
              prepend-icon="search" 
              append-icon="cheveron-down" 
              :loading="searching" 
              :placeholder="__('Search for a user') + '...'" 
              class="w-full"
            />
          </template>
          <template #dropdown>
            <div class="max-h-80 overflow-y-auto">
              <div v-if="searching" class="p-4 text-center">
                <div class="inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-spin text-teal-500">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                  </svg>
                </div>
                <p class="text-sm text-neutral-200 mt-2">{{ __('Searching') }}...</p>
              </div>
              <ul v-else-if="users && users.data && users.data.length" class="py-1">
                <li 
                  v-for="user in users.data" 
                  :key="user.id" 
                  class="flex items-center gap-3 px-4 py-2.5 hover:bg-neutral-800/50 transition-colors"
                >
                  <img 
                    v-if="user.photo" 
                    class="h-8 w-8 rounded-full object-cover flex-shrink-0" 
                    :src="user.photo" 
                    :alt="user.name"
                  />
                  <div v-else class="h-8 w-8 rounded-full bg-neutral-700 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs text-neutral-400">{{ user.name.charAt(0).toUpperCase() }}</span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-neutral-200 truncate">{{ user.name }}</p>
                  </div>
                  <button 
                    v-if="!isModerator(user.id)"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-teal-500 hover:bg-teal-600 rounded-lg transition-colors flex-shrink-0"
                    :title="__('Add')" 
                    @click="attach(user)"
                  >
                    <Icon name="plus" class="h-3 w-3" />
                    {{ __('Add') }}
                  </button>
                  <span v-else class="text-xs text-neutral-400 flex-shrink-0">{{ __('Already added') }}</span>
                </li>
              </ul>
              <div v-else-if="search && search.length >= 2 && !searching" class="p-4 text-center">
                <p class="text-sm text-neutral-200">{{ __('No users found') }}</p>
              </div>
              <div v-else-if="!search || search.length < 2" class="p-4 text-center">
                <p class="text-sm text-neutral-300">{{ __('Type at least 2 characters to search') }}</p>
              </div>
            </div>
          </template>
        </Dropdown>
      </div>

      <!-- Moderators List -->
      <div>
        <div class="flex items-center justify-between mb-2">
          <label class="block text-sm font-medium text-neutral-200">{{ __('Current moderators') }}</label>
          <span class="text-sm text-neutral-300">{{ playlist.moderators.length }}</span>
        </div>
        
        <ul v-if="playlist.moderators.length" class="space-y-2">
          <li 
            v-for="moderator in playlist.moderators" 
            :key="moderator.id" 
            class="flex items-center gap-3 rounded-lg p-2.5 bg-neutral-800/40 border border-neutral-700/50 hover:bg-neutral-800/60 transition-colors"
          >
            <img 
              v-if="moderator.photo" 
              class="h-8 w-8 rounded-full object-cover flex-shrink-0" 
              :src="moderator.photo" 
              :alt="moderator.name"
            />
            <div v-else class="h-8 w-8 rounded-full bg-neutral-700 flex items-center justify-center flex-shrink-0">
              <span class="text-xs text-neutral-400">{{ moderator.name.charAt(0).toUpperCase() }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-neutral-200 truncate">{{ moderator.name }}</p>
              <p v-if="moderator.id === playlist.user_id" class="text-sm text-teal-400">{{ __('Owner') }}</p>
              <p v-else class="text-sm text-neutral-300">{{ __('Moderator') }}</p>
            </div>
            <button 
              v-if="moderator.id !== playlist.user_id"
              class="flex items-center justify-center h-7 w-7 rounded-lg text-neutral-400 hover:text-red-400 hover:bg-red-500/10 transition-colors flex-shrink-0"
              :title="__('Remove moderator')" 
              @click="detach(moderator)"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </li>
        </ul>
        <div v-else class="rounded-lg p-4 bg-neutral-800/20 border border-neutral-700/30 text-center">
          <Icon name="users" class="h-8 w-8 mx-auto mb-2 text-neutral-600" />
          <p class="text-sm text-neutral-200">{{ __('No moderators yet') }}</p>
          <p class="text-sm text-neutral-300 mt-1">{{ __('Add moderators to help manage this playlist') }}</p>
        </div>
      </div>
    </div>
  </Card>

  <!-- Delete Moderator Confirmation Modal -->
  <ConfirmModal
    :show="showDeleteModal"
    :title="__('Remove moderator')"
    :message="moderatorToDelete ? __('Are you sure you want to remove :name as a moderator?', { name: moderatorToDelete.name }) : __('Are you sure you want to remove this moderator?')"
    :confirm-text="__('Remove')"
    :cancel-text="__('Cancel')"
    variant="danger"
    @close="showDeleteModal = false; moderatorToDelete = null"
    @confirm="confirmDetach"
  />
</template>
