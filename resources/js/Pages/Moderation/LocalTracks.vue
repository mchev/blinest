<script setup>
import { ref, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Card from '@/Components/Card.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  tracks: Object, // paginated
  filters: Object,
})

const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.per_page || 15)
const sortBy = ref(props.filters?.sort_by || 'created_at')
const sortDirection = ref(props.filters?.sort_direction || 'desc')
const editingTrack = ref(null)
const editForm = ref({
  track_name: '',
  artist_name: ''
})

// Add watcher for search, perPage, and sorting
watch([search, perPage, sortBy, sortDirection], () => {
  router.get(route('moderation.tracks.index'), 
    { 
      search: search.value, 
      per_page: perPage.value,
      sort_by: sortBy.value,
      sort_direction: sortDirection.value
    }, 
    { preserveState: true, replace: true }
  )
})

function deleteTrack(track) {
  if (confirm(`Êtes-vous sûr de vouloir supprimer la piste "${track.track_name}" ?`)) {
    router.delete(route('moderation.tracks.destroy', track.id), { 
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['tracks'] })
      }
    })
  }
}

function startEditing(track) {
  editingTrack.value = track.id
  editForm.value = {
    track_name: track.track_name,
    artist_name: track.artist_name
  }
}

function cancelEditing() {
  editingTrack.value = null
  editForm.value = {
    track_name: '',
    artist_name: ''
  }
}

function saveEdit(track) {
  router.put(route('moderation.tracks.update', track.id), editForm.value, {
    preserveScroll: true,
    onSuccess: () => {
      editingTrack.value = null
      router.reload({ only: ['tracks'] })
    }
  })
}

function toggleSort(column) {
  if (sortBy.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = column
    sortDirection.value = 'asc'
  }
}
</script>

<template>
  <Layout title="Gestionnaire de pistes">
    <Card class="overflow-hidden">
      <div class="bg-black/20 backdrop-blur-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
          <div class="flex gap-2">
            <input
              v-model="search"
              type="text"
              placeholder="Rechercher par titre ou artiste..."
              class="rounded bg-neutral-800 px-3 py-2 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-teal-500"
            />
            <select v-model="perPage" class="rounded bg-neutral-800 px-2 py-2 text-sm text-white">
              <option :value="10">10</option>
              <option :value="15">15</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
            </select>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-neutral-800">
            <thead>
              <tr>
                <th class="px-4 py-2 text-left text-xs font-semibold text-neutral-400">Pochette</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-neutral-400 cursor-pointer" @click="toggleSort('track_name')">
                  Titre
                  <span v-if="sortBy === 'track_name'" class="ml-1">
                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                  </span>
                </th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-neutral-400 cursor-pointer" @click="toggleSort('artist_name')">
                  Artiste
                  <span v-if="sortBy === 'artist_name'" class="ml-1">
                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                  </span>
                </th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-neutral-400">Audio</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-neutral-400 cursor-pointer" @click="toggleSort('created_at')">
                  Ajouté le
                  <span v-if="sortBy === 'created_at'" class="ml-1">
                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                  </span>
                </th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-neutral-400">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="track in (tracks && tracks.data ? tracks.data : [])" :key="track.id" class="hover:bg-black/10">
                <td class="px-4 py-2">
                  <img v-if="track.artwork_url" :src="track.artwork_url" alt="Pochette" class="h-12 w-12 rounded object-cover border border-neutral-700" />
                  <span v-else class="text-neutral-500 italic">Pas de pochette</span>
                </td>
                <td class="px-4 py-2">
                  <template v-if="editingTrack === track.id">
                    <input
                      v-model="editForm.track_name"
                      type="text"
                      class="w-full rounded bg-neutral-800 px-2 py-1 text-sm text-white focus:outline-none focus:ring-2 focus:ring-teal-500"
                    />
                  </template>
                  <template v-else>
                    <span class="text-white">{{ track.track_name }}</span>
                  </template>
                </td>
                <td class="px-4 py-2">
                  <template v-if="editingTrack === track.id">
                    <input
                      v-model="editForm.artist_name"
                      type="text"
                      class="w-full rounded bg-neutral-800 px-2 py-1 text-sm text-white focus:outline-none focus:ring-2 focus:ring-teal-500"
                    />
                  </template>
                  <template v-else>
                    <span class="text-white">{{ track.artist_name }}</span>
                  </template>
                </td>
                <td class="px-4 py-2">
                  <audio v-if="track.audio_url" :src="track.audio_url" controls class="w-32" />
                  <span v-else class="text-neutral-500 italic">Pas d'audio</span>
                </td>
                <td class="px-4 py-2 text-white">{{ track.created_at }}</td>
                <td class="px-4 py-2">
                  <div class="flex gap-2">
                    <template v-if="editingTrack === track.id">
                      <button @click="saveEdit(track)" class="text-teal-500 hover:text-teal-700 font-semibold">Sauvegarder</button>
                      <button @click="cancelEditing" class="text-neutral-500 hover:text-neutral-700 font-semibold">Annuler</button>
                    </template>
                    <template v-else>
                      <button @click="startEditing(track)" class="text-teal-500 hover:text-teal-700 font-semibold">Modifier</button>
                      <button @click="deleteTrack(track)" class="text-red-500 hover:text-red-700 font-semibold">Supprimer</button>
                    </template>
                  </div>
                </td>
              </tr>
              <tr v-if="!(tracks && tracks.data && tracks.data.length)">
                <td colspan="6" class="px-4 py-6 text-center text-neutral-400">Aucune piste trouvée.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pagination -->
        <div class="mt-6">
          <Pagination :links="tracks.links" />
        </div>
      </div>
    </Card>
  </Layout>
</template> 