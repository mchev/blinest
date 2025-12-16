<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TrashedMessage from '@/Components/TrashedMessage.vue'
import TracksManager from '@/Components/Playlists/TracksManager.vue'
import ModeratorsManager from '@/Components/Playlists/ModeratorsManager.vue'
import RoomsList from '@/Components/Playlists/RoomsList.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  playlist: Object,
  filters: Object,
  answer_types: Object,
  tracks: Object,
  moderators: Object,
})

const form = useForm(props.playlist)

const user = usePage().props.auth.user

const showDeleteModal = ref(false)
const showRestoreModal = ref(false)

const update = () => {
  form.put(`/playlists/${props.playlist.id}`, {
    onSuccess: () => form.reset('password', 'photo'),
  })
}

const destroy = () => {
  showDeleteModal.value = true
}

const confirmDelete = () => {
  router.delete(`/playlists/${props.playlist.id}`)
}

const restore = () => {
  showRestoreModal.value = true
}

const confirmRestore = () => {
  router.put(`/playlists/${props.playlist.id}/restore`)
}
</script>
<template>
  <Head :title="`${form.name}`" />
  <AppLayout>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between w-full gap-4">
      <nav>
        <Link :href="route('playlists')" class="text-neutral-400 hover:text-neutral-200 transition-colors">
          {{ __('Playlists') }}
        </Link>
        <span class="mx-2 text-neutral-600">/</span>
        <span class="text-neutral-300">{{ form.name }}</span>
      </nav>
      <h1 class="text-2xl lg:text-3xl font-bold text-neutral-100 flex-shrink-0">
        {{ form.name }}
      </h1>
    </div>

    <!-- Trashed Message -->
    <trashed-message v-if="playlist.deleted_at" class="mb-6" @restore="restore">
      {{ __('This playlist has been deleted') }}
    </trashed-message>

    <!-- Main Layout -->
    <div class="space-y-6 lg:space-y-0 lg:flex lg:gap-6">
      <!-- Main Content: Tracks Manager (first on mobile, left on desktop) -->
      <div class="flex-1 min-w-0">
        <TracksManager :playlist="playlist" :filters="filters" :tracks="tracks" :answer_types="answer_types" />
      </div>

      <!-- Sidebar: Settings & Info (second on mobile, right on desktop) -->
      <aside v-if="user.id === playlist.user_id" class="w-full lg:w-80 xl:w-96 flex-shrink-0 space-y-4 lg:space-y-6">
        <!-- Playlist Statistics Summary -->
        <Card>
          <template #header>
            <div>
              <h3 class="text-lg font-bold text-neutral-200">{{ __('Statistics') }}</h3>
              <p class="text-sm text-neutral-200 mt-0.5">{{ __('Playlist overview') }}</p>
            </div>
          </template>
          
          <div class="p-4 lg:p-5 space-y-4">
            <!-- Total Tracks -->
            <div class="flex items-center justify-between rounded-lg bg-neutral-800/40 p-3 border border-neutral-700/50">
              <div class="flex items-center gap-2">
                <Icon name="music" class="h-5 w-5 text-teal-400" />
                <span class="text-sm font-medium text-neutral-200">{{ __('Total tracks') }}</span>
              </div>
              <span class="text-lg font-bold text-neutral-100">{{ playlist.total_tracks || 0 }}</span>
            </div>

            <!-- Difficulty Distribution -->
            <div class="space-y-2">
              <h4 class="text-sm font-semibold text-neutral-200 uppercase tracking-wide">{{ __('Difficulty distribution') }}</h4>
              <div class="space-y-2">
                <div v-for="(difficulty, key) in { Easy: 'Easy', Medium: 'Medium', Difficult: 'Difficult', Expert: 'Expert' }" :key="key">
                  <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-neutral-200 font-medium">{{ __(difficulty) }}</span>
                    <span class="text-neutral-100 font-medium">
                      {{ playlist.difficulties[difficulty] || 0 }} 
                      <span class="text-neutral-300">
                        ({{ playlist.total_tracks > 0 ? Math.round((playlist.difficulties[difficulty] || 0) / playlist.total_tracks * 100) : 0 }}%)
                      </span>
                    </span>
                  </div>
                  <div class="h-2 bg-neutral-800 rounded-full overflow-hidden">
                    <div 
                      :class="{
                        'bg-teal-400': difficulty === 'Easy',
                        'bg-yellow-400': difficulty === 'Medium',
                        'bg-orange-400': difficulty === 'Difficult',
                        'bg-red-400': difficulty === 'Expert',
                      }"
                      class="h-full transition-all duration-300"
                      :style="{ width: playlist.total_tracks > 0 ? `${Math.round((playlist.difficulties[difficulty] || 0) / playlist.total_tracks * 100)}%` : '0%' }"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Card>

        <!-- Playlist Settings Card -->
        <Card>
          <template #header>
            <div>
              <h3 class="text-lg font-bold text-neutral-200">{{ __('Playlist settings') }}</h3>
              <p class="text-sm text-neutral-200 mt-0.5">{{ __('Edit playlist information and transfer ownership') }}</p>
            </div>
          </template>
          
          <form id="playlistForm" class="p-4 lg:p-5 space-y-5" @submit.prevent="update">
            <!-- Title -->
            <div>
              <text-input 
                v-model="form.name" 
                :error="form.errors.name" 
                class="w-full" 
                :label="__('Title')" 
              />
            </div>
            
            <!-- Description -->
            <div>
              <textarea-input 
                v-model="form.description" 
                :error="form.errors.description" 
                class="w-full" 
                :label="__('Description')" 
              />
            </div>
            
            <!-- Owner Transfer -->
            <div class="rounded-lg border border-neutral-700/50 bg-neutral-800/40 p-3">
              <select-input 
                v-model="form.user_id" 
                :error="form.errors.user_id" 
                class="w-full mb-2" 
                :label="__('Owner')"
              >
                <option v-for="moderator in playlist.moderators" :value="moderator.id">
                  {{ moderator.name }}
                </option>
              </select-input>
              <p class="text-sm text-neutral-200 leading-relaxed">
                {{ __('Transfer the playlist management to a moderator') }}
              </p>
            </div>
          </form>
          
          <template #footer>
            <div class="flex items-center justify-between gap-3">
              <button 
                v-if="!playlist.deleted_at" 
                class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all" 
                tabindex="-1" 
                type="button" 
                @click="destroy"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
                {{ __('Delete') }}
              </button>
              <loading-button 
                :loading="form.processing" 
                class="btn-primary px-4 py-2 text-sm font-medium" 
                form="playlistForm" 
                type="submit"
              >
                {{ __('Update') }}
              </loading-button>
            </div>
          </template>
        </Card>

        <!-- Moderators Manager -->
        <ModeratorsManager :playlist="playlist" />

        <!-- Rooms List -->
        <RoomsList :playlist="playlist" />
      </aside>
    </div>

    <!-- Delete Confirmation Modal -->
    <ConfirmModal
      :show="showDeleteModal"
      :title="__('Delete playlist')"
      :message="__('Are you sure you want to delete this playlist? This action cannot be undone.')"
      :confirm-text="__('Delete')"
      :cancel-text="__('Cancel')"
      variant="danger"
      @close="showDeleteModal = false"
      @confirm="confirmDelete"
    />

    <!-- Restore Confirmation Modal -->
    <ConfirmModal
      :show="showRestoreModal"
      :title="__('Restore playlist')"
      :message="__('Are you sure you want to restore this playlist?')"
      :confirm-text="__('Restore')"
      :cancel-text="__('Cancel')"
      variant="info"
      @close="showRestoreModal = false"
      @confirm="confirmRestore"
    />
  </AppLayout>
</template>
