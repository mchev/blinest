<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Card from '@/Components/Card.vue'
import debounce from 'lodash/debounce'

const props = defineProps({
  roomsWithModerators: Array,
  filters: Object,
  stats: Object,
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

const debouncedSearch = debounce(() => {
  router.get(
    route('moderation.moderators'),
    { search: search.value },
    { preserveState: true, preserveScroll: true }
  )
}, 300)
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
                <label for="showOnlyInactive" class="text-sm text-neutral-300">Afficher uniquement les modérateurs inactifs (>8 mois)</label>
              </div>
              <form @submit.prevent="submitSearch" class="flex gap-2">
                <input
                  v-model="search"
                  type="text"
                  placeholder="Rechercher un modérateur..."
                  class="w-full px-4 py-2 border rounded-lg"
                  @input="debouncedSearch"
                />
                <button type="submit" class="rounded bg-teal-500 px-4 py-2 text-white font-semibold hover:bg-teal-600 transition">Rechercher</button>
              </form>
            </div>
          </div>
          
          <!-- Stats overview -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-neutral-800/50 rounded-lg p-4">
              <div class="text-sm text-neutral-400 mb-1">Salles publiques</div>
              <div class="text-2xl font-bold text-white">{{ stats.total_rooms }}</div>
            </div>
            <div class="bg-neutral-800/50 rounded-lg p-4">
              <div class="text-sm text-neutral-400 mb-1">Modérateurs actifs</div>
              <div class="text-2xl font-bold text-white">{{ stats.active_moderators }}</div>
            </div>
            <div class="bg-neutral-800/50 rounded-lg p-4">
              <div class="text-sm text-neutral-400 mb-1">Modérateurs inactifs</div>
              <div class="text-2xl font-bold text-white">{{ stats.inactive_moderators }}</div>
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
                  <div>
                    <h3 class="text-lg font-semibold text-white">{{ room.name }}</h3>
                    <p class="text-xs text-neutral-400">Créée le {{ room.created_at }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-sm text-neutral-400">{{ room.moderators_count }} modérateur(s)</div>
                </div>
              </div>
            </div>

            <!-- Moderators list -->
            <div class="p-4">
              <div v-if="room.moderators.length" class="space-y-4">
                <div v-for="moderator in room.moderators" :key="moderator.id" 
                     class="bg-neutral-800/50 rounded-lg p-4 hover:bg-neutral-700/60 transition-all duration-200 shadow-sm"
                     :class="{ 'border-l-4 border-amber-500/70 opacity-60': moderator.is_inactive, 'border-l-4 border-teal-500': !moderator.is_inactive }">
                  <div class="flex items-start gap-4">
                    <img v-if="moderator.photo" :src="moderator.photo" 
                         class="h-12 w-12 rounded-full object-cover border-2 border-teal-500 shadow-md" />
                    <div v-else class="h-12 w-12 rounded-full bg-neutral-700 flex items-center justify-center text-white font-bold text-lg border-2 border-teal-500 shadow-md">
                      {{ moderator.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                        <h4 class="text-white font-medium truncate text-lg">{{ moderator.name }}</h4>
                        <div class="flex items-center gap-3 mt-1 sm:mt-0">
                          <span class="text-xs bg-neutral-700/70 px-2 py-1 rounded-full text-teal-300 font-medium">
                            {{ moderator.moderated_rooms_count }} salle(s)
                          </span>
                          <span class="text-xs bg-neutral-700/70 px-2 py-1 rounded-full text-teal-300 font-medium">
                            {{ moderator.moderated_playlists_count }} playlist(s)
                          </span>
                        </div>
                      </div>
                      
                      <!-- Activity indicators -->
                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs mt-2">
                        <div class="flex items-center text-neutral-300 bg-neutral-800/80 rounded-md p-2">
                          <svg class="w-4 h-4 mr-2 text-teal-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          <span class="truncate">{{ moderator.last_connection }}</span>
                        </div>
                        <div class="flex items-center text-neutral-300 bg-neutral-800/80 rounded-md p-2">
                          <svg class="w-4 h-4 mr-2 text-teal-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                          </svg>
                          <span class="truncate">{{ moderator.last_game_activity }}</span>
                        </div>
                        <div class="flex items-center text-neutral-300 bg-neutral-800/80 rounded-md p-2">
                          <svg class="w-4 h-4 mr-2 text-teal-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                          </svg>
                          <span class="truncate">{{ moderator.last_message_date }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="text-neutral-400 italic text-center py-8 bg-neutral-800/30 rounded-lg">
                <svg class="w-10 h-10 mx-auto mb-2 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p>Aucun modérateur pour cette salle</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </Layout>
</template> 