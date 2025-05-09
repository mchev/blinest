<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  roomsWithModerators: Array,
  filters: Object,
})

const search = ref(props.filters?.search || '')
const showOnlyInactive = ref(false)

const filteredRooms = computed(() => {
  if (showOnlyInactive.value) {
    return props.roomsWithModerators.map(room => ({
      ...room,
      moderators: room.moderators.filter(mod => mod.is_inactive)
    })).filter(room => room.moderators.length > 0)
  }
  return props.roomsWithModerators
})

function submitSearch() {
  router.get(route('moderation.moderators.index'), { search: search.value }, { preserveState: true, replace: true })
}
</script>

<template>
  <Layout title="Modérateurs">
    <Card class="overflow-hidden">
      <div class="bg-black/20 backdrop-blur-sm p-6">
        <!-- Header with stats -->
        <div class="mb-8">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <h2 class="text-2xl font-bold text-white mb-4 sm:mb-0">Modérateurs</h2>
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex items-center gap-2">
                <input
                  type="checkbox"
                  id="showOnlyInactive"
                  v-model="showOnlyInactive"
                  class="rounded border-neutral-700 bg-neutral-800 text-teal-500 focus:ring-teal-500"
                />
                <label for="showOnlyInactive" class="text-sm text-neutral-300">Afficher uniquement les modérateurs inactifs (>6 mois)</label>
              </div>
              <form @submit.prevent="submitSearch" class="flex gap-2">
                <input
                  v-model="search"
                  type="text"
                  placeholder="Rechercher des modérateurs..."
                  class="rounded bg-neutral-800 px-3 py-2 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-teal-500"
                />
                <button type="submit" class="rounded bg-teal-500 px-4 py-2 text-white font-semibold hover:bg-teal-600 transition">Rechercher</button>
              </form>
            </div>
          </div>
          
          <!-- Stats overview -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-neutral-800/50 rounded-lg p-4">
              <div class="text-sm text-neutral-400 mb-1">Salles publiques</div>
              <div class="text-2xl font-bold text-white">{{ roomsWithModerators.length }}</div>
            </div>
            <div class="bg-neutral-800/50 rounded-lg p-4">
              <div class="text-sm text-neutral-400 mb-1">Modérateurs actifs</div>
              <div class="text-2xl font-bold text-white">
                {{ roomsWithModerators.reduce((acc, room) => acc + room.moderators.filter(m => !m.is_inactive).length, 0) }}
              </div>
            </div>
            <div class="bg-neutral-800/50 rounded-lg p-4">
              <div class="text-sm text-neutral-400 mb-1">Modérateurs inactifs</div>
              <div class="text-2xl font-bold text-white">
                {{ roomsWithModerators.reduce((acc, room) => acc + room.moderators.filter(m => m.is_inactive).length, 0) }}
              </div>
            </div>
          </div>
        </div>

        <!-- Rooms and moderators list -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div v-for="room in filteredRooms" :key="room.id" 
               class="bg-neutral-800/30 rounded-lg overflow-hidden">
            <!-- Room header -->
            <div class="bg-neutral-800/50 p-4 border-b border-neutral-700">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                  </svg>
                  <h3 class="text-lg font-semibold text-white">{{ room.name }}</h3>
                </div>
                <span class="text-sm text-neutral-400">{{ room.moderators.length }} modérateur(s)</span>
              </div>
            </div>

            <!-- Moderators list -->
            <div class="p-4">
              <div v-if="room.moderators.length" class="space-y-3">
                <div v-for="moderator in room.moderators" :key="moderator.id" 
                     class="bg-neutral-800/50 rounded-lg p-3 hover:bg-neutral-800/70 transition-colors"
                     :class="{ 'opacity-50': moderator.is_inactive }">
                  <div class="flex items-start gap-3">
                    <img v-if="moderator.photo" :src="moderator.photo" 
                         class="h-10 w-10 rounded-full object-cover border-2 border-teal-500" />
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <h4 class="text-white font-medium truncate">{{ moderator.name }}</h4>
                        <span class="text-xs text-neutral-400">{{ moderator.moderated_rooms_count }} salle(s)</span>
                      </div>
                      <p class="text-sm text-neutral-400 truncate mb-2">{{ moderator.email }}</p>
                      
                      <!-- Activity indicators -->
                      <div class="grid grid-cols-3 gap-2 text-xs">
                        <div class="flex items-center text-neutral-300">
                          <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          {{ moderator.last_connection }}
                        </div>
                        <div class="flex items-center text-neutral-300">
                          <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                          </svg>
                          {{ moderator.last_game_activity }}
                        </div>
                        <div class="flex items-center text-neutral-300">
                          <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                          </svg>
                          {{ moderator.last_track_added }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="text-neutral-400 italic text-center py-4">
                Aucun modérateur pour cette salle
              </div>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </Layout>
</template> 