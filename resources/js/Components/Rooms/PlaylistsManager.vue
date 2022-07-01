<script setup>
import { ref, watch, onMounted } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  room: Object,
  playlists: Object,
})

const form = useForm({
  playlist_id: null,
})

const attach = (playlist) => {
  form
    .transform((data) => ({
      playlist_id: playlist.id,
    }))
    .post(`/rooms/${props.room.id}/playlists/attach`, {
      preserveScroll: true,
    })
}

const detach = (playlist) => {
  form
    .transform((data) => ({
      playlist_id: playlist.id,
    }))
    .delete(`/rooms/${props.room.id}/playlists/detach`, {
      preserveScroll: true,
    })
}
</script>
<template>
  <Card>
    <template #header>
      <h3 class="text-xl font-bold">{{ __('Playlists') }}</h3>
    </template>

    <dropdown placement="bottom-start" class="mb-2 border-b pb-2" @closed="search = ''">
      <template #default>
        <button type="button" class="btn-secondary">{{ __('Attach a playlist') }}</button>
      </template>
      <template #dropdown>
        <ul v-if="playlists && playlists.length" class="max-w-50 max-h-80 overflow-y-auto">
          <li v-for="playlist in playlists" :key="playlist.id" class="flex items-center p-2">
            <span class="mr-4">{{ playlist.name }}</span>
            <button class="ml-auto flex items-center rounded-full bg-blue-500 py-1 px-2 text-xs uppercase" :title="__('Add')" @click="attach(playlist)">
              <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
              </svg>
              {{ __('Attach') }}
            </button>
          </li>
        </ul>
      </template>
    </dropdown>

    <ul v-if="room.playlists && room.playlists.length">
      <li v-for="playlist in room.playlists" :key="playlist.id" class="flex items-center rounded p-3 hover:bg-neutral-200">
        {{ playlist.name }}
        <button class="ml-auto flex items-center text-red-500" :title="__('Detach')" @click="detach(playlist)">
          <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-6 w-6" viewBox="0 0 24 24">
            <g>
              <path fill="currentColor" d="M17.657 14.828l-1.414-1.414L17.657 12A4 4 0 1 0 12 6.343l-1.414 1.414-1.414-1.414 1.414-1.414a6 6 0 0 1 8.485 8.485l-1.414 1.414zm-2.829 2.829l-1.414 1.414a6 6 0 1 1-8.485-8.485l1.414-1.414 1.414 1.414L6.343 12A4 4 0 1 0 12 17.657l1.414-1.414 1.414 1.414zm0-9.9l1.415 1.415-7.071 7.07-1.415-1.414 7.071-7.07zM5.775 2.293l1.932-.518L8.742 5.64l-1.931.518-1.036-3.864zm9.483 16.068l1.931-.518 1.036 3.864-1.932.518-1.035-3.864zM2.293 5.775l3.864 1.036-.518 1.931-3.864-1.035.518-1.932zm16.068 9.483l3.864 1.035-.518 1.932-3.864-1.036.518-1.931z" />
            </g>
          </svg>
          {{ __('Detach') }}
        </button>
      </li>
    </ul>
    <p v-else class="my-2 text-sm text-neutral-400">{{ __('No playlist') }}</p>
  </Card>
</template>
